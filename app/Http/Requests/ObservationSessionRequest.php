<?php

namespace App\Http\Requests;

use App\Enums\EquipmentAvailability;
use App\Enums\SessionStatus;
use App\Models\Equipment;
use App\Models\SessionLocationDetail;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ObservationSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $sessionId = $this->route('session')?->id;

        return [
            'code' => ['required', 'string', 'max:30', Rule::unique('observation_sessions', 'code')->ignore($sessionId)],
            'name' => ['required', 'string', 'max:150'],
            'astronomical_object_id' => ['required', 'exists:astronomical_objects,id'],
            'observation_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'different:start_time'],
            'status' => ['required', Rule::enum(SessionStatus::class)],
            'description' => ['nullable', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:2000'],

            'location' => ['required', 'array'],
            'location.observation_location_id' => ['required', 'exists:observation_locations,id'],
            'location.weather_condition' => ['required', Rule::in(SessionLocationDetail::WEATHER)],
            'location.visibility_condition' => ['required', Rule::in(SessionLocationDetail::VISIBILITY)],
            'location.temperature' => ['nullable', 'numeric', 'between:-60,60'],
            'location.humidity' => ['nullable', 'integer', 'between:0,100'],
            'location.light_pollution_level' => ['nullable', 'integer', 'between:1,9'],
            'location.environment_notes' => ['nullable', 'string', 'max:2000'],

            'equipment' => ['nullable', 'array'],
            'equipment.*.selected' => ['nullable', 'boolean'],
            'equipment.*.quantity_used' => ['nullable', 'integer', 'min:1', 'max:9999'],
            'equipment.*.usage_notes' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'kode sesi',
            'name' => 'nama sesi',
            'astronomical_object_id' => 'objek sasaran',
            'observation_date' => 'tanggal pengamatan',
            'start_time' => 'waktu mulai',
            'end_time' => 'waktu selesai',
            'status' => 'status sesi',
            'description' => 'deskripsi',
            'notes' => 'catatan',
            'location.observation_location_id' => 'lokasi pengamatan',
            'location.weather_condition' => 'kondisi cuaca',
            'location.visibility_condition' => 'kondisi visibilitas',
            'location.temperature' => 'suhu',
            'location.humidity' => 'kelembapan',
            'location.light_pollution_level' => 'tingkat polusi cahaya',
            'location.environment_notes' => 'catatan lingkungan',
            'equipment.*.quantity_used' => 'jumlah perlengkapan',
            'equipment.*.usage_notes' => 'catatan penggunaan',
        ];
    }

    public function messages(): array
    {
        return ['end_time.different' => 'Waktu selesai harus berbeda dari waktu mulai.'];
    }

    /** Validasi lintas-tabel: perlengkapan terpilih harus tersedia dan stok mencukupi. */
    public function after(): array
    {
        return [function (Validator $validator) {
            $selected = $this->selectedEquipment();
            if ($selected === []) {
                return;
            }

            $items = Equipment::whereIn('id', array_keys($selected))->get()->keyBy('id');

            foreach ($selected as $id => $row) {
                $item = $items->get($id);
                if (! $item) {
                    $validator->errors()->add("equipment.$id", 'Perlengkapan tidak ditemukan.');
                } elseif ($item->availability_status === EquipmentAvailability::Maintenance) {
                    $validator->errors()->add("equipment.$id", "{$item->name} sedang dalam perawatan dan tidak dapat dipakai.");
                } elseif ($row['quantity_used'] > $item->quantity) {
                    $validator->errors()->add("equipment.$id.quantity_used", "Jumlah {$item->name} melebihi stok ({$item->quantity}).");
                }
            }
        }];
    }

    /** Perlengkapan yang dicentang, dalam format siap sync: [id => [quantity_used, usage_notes]]. */
    public function selectedEquipment(): array
    {
        $result = [];
        foreach ((array) $this->input('equipment', []) as $id => $row) {
            if (! empty($row['selected'])) {
                $result[(int) $id] = [
                    'quantity_used' => (int) (($row['quantity_used'] ?? null) ?: 1),
                    'usage_notes' => ($row['usage_notes'] ?? null) ?: null,
                ];
            }
        }

        return $result;
    }

    public function sessionData(): array
    {
        return $this->safe()->only([
            'code', 'name', 'astronomical_object_id', 'observation_date',
            'start_time', 'end_time', 'status', 'description', 'notes',
        ]);
    }

    public function locationData(): array
    {
        return $this->validated('location');
    }
}
