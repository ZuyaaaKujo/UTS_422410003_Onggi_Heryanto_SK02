<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ObservationLocation extends Model
{
    public const SKY_CONDITIONS = [
        'Sangat Gelap', 'Gelap', 'Pinggiran Kota', 'Perkotaan', 'Dataran Tinggi Cerah', 'Pesisir Cerah',
    ];

    protected $fillable = [
        'code', 'name', 'country', 'province', 'city', 'latitude', 'longitude',
        'elevation', 'sky_condition', 'light_pollution_level', 'description',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'elevation' => 'integer',
            'light_pollution_level' => 'integer',
        ];
    }

    public function sessionDetails(): HasMany
    {
        return $this->hasMany(SessionLocationDetail::class);
    }

    public function getBortleLabelAttribute(): string
    {
        return match (true) {
            $this->light_pollution_level <= 2 => 'Langit Sangat Gelap',
            $this->light_pollution_level <= 4 => 'Langit Pedesaan',
            $this->light_pollution_level <= 6 => 'Pinggiran Kota',
            default => 'Langit Kota',
        };
    }

    public function getBortleColorAttribute(): string
    {
        return match (true) {
            $this->light_pollution_level <= 2 => 'success',
            $this->light_pollution_level <= 4 => 'info',
            $this->light_pollution_level <= 6 => 'warning',
            default => 'danger',
        };
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, fn ($q) => $q->where(function ($w) use ($term) {
            $w->where('name', 'like', "%{$term}%")
                ->orWhere('code', 'like', "%{$term}%")
                ->orWhere('city', 'like', "%{$term}%")
                ->orWhere('province', 'like', "%{$term}%");
        }));
    }
}
