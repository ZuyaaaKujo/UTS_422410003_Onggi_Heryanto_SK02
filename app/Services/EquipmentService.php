<?php

namespace App\Services;

use App\Models\Equipment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EquipmentService
{
    public function paginate(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return Equipment::query()
            ->with('category')
            ->withCount('sessions')
            ->filter($filters)
            ->orderBy('code')
            ->paginate($perPage)
            ->withQueryString();
    }

    /** Ringkasan inventaris untuk halaman daftar. */
    public function summary(): array
    {
        return [
            'items' => Equipment::count(),
            'units' => (int) Equipment::sum('quantity'),
            'available' => Equipment::where('availability_status', 'available')->count(),
            'in_use' => Equipment::where('availability_status', 'in_use')->count(),
            'maintenance' => Equipment::where('availability_status', 'maintenance')->count(),
        ];
    }

    public function nextCode(): string
    {
        $last = Equipment::where('code', 'like', 'EQ-%')->orderByDesc('code')->value('code');

        return sprintf('EQ-%03d', $last ? ((int) substr($last, -3)) + 1 : 1);
    }

    public function store(array $data): Equipment
    {
        return Equipment::create($data);
    }

    public function update(Equipment $equipment, array $data): Equipment
    {
        $equipment->update($data);

        return $equipment;
    }

    public function canDelete(Equipment $equipment): bool
    {
        return ! $equipment->sessions()->exists();
    }

    public function delete(Equipment $equipment): void
    {
        $equipment->delete();
    }
}
