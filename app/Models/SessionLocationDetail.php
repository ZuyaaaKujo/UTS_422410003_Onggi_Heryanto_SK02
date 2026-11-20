<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionLocationDetail extends Model
{
    public const WEATHER = [
        'Cerah', 'Cerah Berawan', 'Berawan', 'Berkabut', 'Hujan Ringan', 'Angin Kencang',
    ];

    public const VISIBILITY = ['Sangat Baik', 'Baik', 'Cukup', 'Buruk'];

    protected $fillable = [
        'observation_session_id', 'observation_location_id', 'weather_condition',
        'visibility_condition', 'temperature', 'humidity', 'light_pollution_level', 'environment_notes',
    ];

    protected function casts(): array
    {
        return ['temperature' => 'float'];
    }

    public function observationSession(): BelongsTo
    {
        return $this->belongsTo(ObservationSession::class);
    }

    public function observationLocation(): BelongsTo
    {
        return $this->belongsTo(ObservationLocation::class);
    }
}
