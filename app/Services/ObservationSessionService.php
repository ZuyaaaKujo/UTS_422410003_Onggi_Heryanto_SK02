<?php

namespace App\Services;

use App\Enums\EquipmentAvailability;
use App\Models\Equipment;
use App\Models\ObservationSession;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ObservationSessionService
{
    public function query(array $filters): Builder
    {
        return ObservationSession::query()
            ->with(['astronomicalObject', 'locationDetail.observationLocation'])
            ->withCount('equipment')
            ->filter($filters)
            ->orderByDesc('observation_date')
            ->orderByDesc('start_time');
    }

    public function paginate(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->query($filters)->paginate($perPage)->withQueryString();
    }

    /** Menyarankan kode sesi berikutnya, contoh: OBS-2026-021. */
    public function nextCode(): string
    {
        $year = now()->year;
        $last = ObservationSession::where('code', 'like', "OBS-{$year}-%")->orderByDesc('code')->value('code');
        $number = $last ? ((int) substr($last, -3)) + 1 : 1;

        return sprintf('OBS-%d-%03d', $year, $number);
    }

    /**
     * Membuat sesi + detail lokasi (One-to-One) + perlengkapan (Many-to-Many) dalam satu transaksi.
     *
     * @param array $equipment format [equipment_id => ['quantity_used' => int, 'usage_notes' => ?string]]
     */
    public function create(array $data, array $locationDetail, array $equipment): ObservationSession
    {
        return DB::transaction(function () use ($data, $locationDetail, $equipment) {
            $session = ObservationSession::create($data);
            $session->locationDetail()->create($locationDetail);
            $session->equipment()->sync($equipment);

            $this->syncAvailability(array_keys($equipment));

            return $session;
        });
    }

    public function update(ObservationSession $session, array $data, array $locationDetail, array $equipment): ObservationSession
    {
        return DB::transaction(function () use ($session, $data, $locationDetail, $equipment) {
            $previous = $session->equipment()->pluck('equipment.id')->all();

            $session->update($data);
            $session->locationDetail()->updateOrCreate(
                ['observation_session_id' => $session->id],
                $locationDetail
            );
            $session->equipment()->sync($equipment);

            $this->syncAvailability(array_unique([...$previous, ...array_keys($equipment)]));

            return $session;
        });
    }

    public function delete(ObservationSession $session): void
    {
        DB::transaction(function () use ($session) {
            $equipmentIds = $session->equipment()->pluck('equipment.id')->all();
            $session->delete(); // detail lokasi & pivot terhapus oleh ON DELETE CASCADE
            $this->syncAvailability($equipmentIds);
        });
    }

    /**
     * Status ketersediaan perlengkapan mengikuti sesi: dipakai sesi aktif => "Sedang Digunakan",
     * selain itu "Tersedia". Perlengkapan yang sedang dalam perawatan tidak diubah.
     */
    public function syncAvailability(array $equipmentIds): void
    {
        if ($equipmentIds === []) {
            return;
        }

        Equipment::whereIn('id', $equipmentIds)
            ->where('availability_status', '!=', EquipmentAvailability::Maintenance->value)
            ->get()
            ->each(function (Equipment $item) {
                $inUse = $item->sessions()->where('status', 'active')->exists();
                $item->update([
                    'availability_status' => $inUse ? EquipmentAvailability::InUse : EquipmentAvailability::Available,
                ]);
            });
    }

    /** Baris-baris untuk ekspor CSV. */
    public function exportRows(array $filters): \Generator
    {
        yield ['Kode', 'Nama Sesi', 'Objek Sasaran', 'Tanggal', 'Mulai', 'Selesai', 'Status', 'Lokasi', 'Cuaca', 'Visibilitas', 'Perlengkapan'];

        foreach ($this->query($filters)->with('equipment')->cursor() as $s) {
            yield [
                $s->code, $s->name, $s->astronomicalObject?->name, $s->observation_date->format('Y-m-d'),
                $s->start_time_short, $s->end_time_short, $s->status->label(),
                $s->locationDetail?->observationLocation?->name, $s->locationDetail?->weather_condition,
                $s->locationDetail?->visibility_condition, $s->equipment->pluck('name')->implode('; '),
            ];
        }
    }
}
