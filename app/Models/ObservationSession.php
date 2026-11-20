<?php

namespace App\Models;

use App\Enums\SessionStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ObservationSession extends Model
{
    protected $fillable = [
        'code', 'name', 'astronomical_object_id', 'observation_date',
        'start_time', 'end_time', 'status', 'description', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'observation_date' => 'date',
            'status' => SessionStatus::class,
        ];
    }

    public function astronomicalObject(): BelongsTo
    {
        return $this->belongsTo(AstronomicalObject::class);
    }

    /** One-to-One: detail lokasi sesi. */
    public function locationDetail(): HasOne
    {
        return $this->hasOne(SessionLocationDetail::class);
    }

    /** Many-to-Many: perlengkapan yang digunakan pada sesi (pivot equipment_session). */
    public function equipment(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class, 'equipment_session')
            ->withPivot('quantity_used', 'usage_notes')
            ->withTimestamps();
    }

    public function getStartTimeShortAttribute(): string
    {
        return substr((string) $this->start_time, 0, 5);
    }

    public function getEndTimeShortAttribute(): string
    {
        return substr((string) $this->end_time, 0, 5);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, fn ($q) => $q->where(function ($w) use ($term) {
            $w->where('code', 'like', "%{$term}%")->orWhere('name', 'like', "%{$term}%");
        }));
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->search($filters['search'] ?? null)
            ->when($filters['object'] ?? null, fn ($q, $v) => $q->where('astronomical_object_id', $v))
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($filters['date'] ?? null, fn ($q, $v) => $q->whereDate('observation_date', $v));
    }
}
