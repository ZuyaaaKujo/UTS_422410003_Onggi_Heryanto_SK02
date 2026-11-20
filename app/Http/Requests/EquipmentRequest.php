<?php

namespace App\Http\Requests;

use App\Enums\EquipmentAvailability;
use App\Enums\EquipmentCondition;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', Rule::unique('equipment', 'code')->ignore($this->route('equipment')?->id)],
            'name' => ['required', 'string', 'max:150'],
            'equipment_category_id' => ['required', 'exists:equipment_categories,id'],
            'brand' => ['nullable', 'string', 'max:80'],
            'model' => ['nullable', 'string', 'max:80'],
            'quantity' => ['required', 'integer', 'min:0', 'max:9999'],
            'condition' => ['required', Rule::enum(EquipmentCondition::class)],
            'availability_status' => ['required', Rule::enum(EquipmentAvailability::class)],
            'description' => ['nullable', 'string', 'max:3000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'kode perlengkapan', 'name' => 'nama perlengkapan', 'equipment_category_id' => 'kategori',
            'brand' => 'merek', 'model' => 'model', 'quantity' => 'jumlah', 'condition' => 'kondisi',
            'availability_status' => 'status ketersediaan', 'description' => 'deskripsi',
        ];
    }
}
