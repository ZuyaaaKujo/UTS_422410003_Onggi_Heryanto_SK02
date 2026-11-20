<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ObservationLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', Rule::unique('observation_locations', 'code')->ignore($this->route('location')?->id)],
            'name' => ['required', 'string', 'max:150'],
            'country' => ['required', 'string', 'max:80'],
            'province' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'elevation' => ['required', 'integer', 'between:-500,9000'],
            'sky_condition' => ['nullable', 'string', 'max:60'],
            'light_pollution_level' => ['required', 'integer', 'between:1,9'],
            'description' => ['nullable', 'string', 'max:3000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'kode lokasi', 'name' => 'nama lokasi', 'country' => 'negara', 'province' => 'provinsi',
            'city' => 'kota', 'latitude' => 'latitude', 'longitude' => 'longitude', 'elevation' => 'elevasi',
            'sky_condition' => 'kondisi langit', 'light_pollution_level' => 'tingkat polusi cahaya',
            'description' => 'deskripsi',
        ];
    }
}
