<?php

namespace App\Services;

use App\Models\AstronomicalObject;
use App\Models\Equipment;
use App\Models\ObservationLocation;
use App\Models\ObservationSession;

class SearchService
{
    public function search(string $term, int $limit = 8): array
    {
        return [
            'sessions' => ObservationSession::with('astronomicalObject')->search($term)->latest('observation_date')->limit($limit)->get(),
            'objects' => AstronomicalObject::search($term)->orderBy('name')->limit($limit)->get(),
            'locations' => ObservationLocation::search($term)->orderBy('name')->limit($limit)->get(),
            'equipment' => Equipment::with('category')->search($term)->orderBy('name')->limit($limit)->get(),
        ];
    }
}
