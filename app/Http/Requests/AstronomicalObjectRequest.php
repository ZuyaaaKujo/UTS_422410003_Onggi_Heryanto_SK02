<?php

namespace App\Http\Requests;

use App\Enums\ObjectCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AstronomicalObjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', Rule::unique('astronomical_objects', 'code')->ignore($this->route('astronomical_object')?->id)],
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', Rule::enum(ObjectCategory::class)],
            'constellation' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:3000'],
            'astronomical_info' => ['nullable', 'string', 'max:3000'],
            'image_url' => ['nullable', 'url', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'kode objek', 'name' => 'nama objek', 'category' => 'kategori',
            'constellation' => 'konstelasi', 'description' => 'deskripsi',
            'astronomical_info' => 'informasi astronomi', 'image_url' => 'URL gambar',
        ];
    }
}
