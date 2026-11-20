<?php

namespace App\Models;

use App\Enums\EquipmentAvailability;
use App\Enums\EquipmentCondition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Equipment extends Model
{
    protected $table = 'equipment';

    protected $fillable = [
        'code', 'name', 'equipment_category_id', 'brand', 'model',
        'quantity', 'condition', 'availability_status', 'description',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'condition' => EquipmentCondition::class,
            'availability_status' => EquipmentAvailability::class,
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(EquipmentCategory::class, 'equipment_category_id');
    }

    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(ObservationSession::class, 'equipment_session')
            ->withPivot('quantity_used', 'usage_notes')
            ->withTimestamps();
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, fn ($q) => $q->where(function ($w) use ($term) {
            $w->where('name', 'like', "%{$term}%")
                ->orWhere('code', 'like', "%{$term}%")
                ->orWhere('brand', 'like', "%{$term}%")
                ->orWhere('model', 'like', "%{$term}%");
        }));
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->search($filters['search'] ?? null)
            ->when($filters['category'] ?? null, fn ($q, $v) => $q->where('equipment_category_id', $v))
            ->when($filters['condition'] ?? null, fn ($q, $v) => $q->where('condition', $v))
            ->when($filters['availability'] ?? null, fn ($q, $v) => $q->where('availability_status', $v));
    }
}
