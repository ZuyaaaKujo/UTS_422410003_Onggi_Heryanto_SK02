<?php

namespace App\Models;

use App\Enums\ObjectCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AstronomicalObject extends Model
{
    protected $fillable = [
        'code', 'name', 'category', 'constellation',
        'description', 'astronomical_info', 'image_url',
    ];

    protected function casts(): array
    {
        return ['category' => ObjectCategory::class];
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(ObservationSession::class);
    }

    /** URL gambar: gambar yang diisi pengguna, atau fallback lokal per kategori. */
    public function getImageSrcAttribute(): string
    {
        return $this->image_url ?: $this->category->fallbackImage();
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, fn ($q) => $q->where(function ($w) use ($term) {
            $w->where('name', 'like', "%{$term}%")
                ->orWhere('code', 'like', "%{$term}%")
                ->orWhere('constellation', 'like', "%{$term}%");
        }));
    }

    public function scopeOfCategory(Builder $query, ?string $category): Builder
    {
        return $query->when($category, fn ($q) => $q->where('category', $category));
    }
}
