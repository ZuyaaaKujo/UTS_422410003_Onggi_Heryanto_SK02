<?php

namespace App\Services;

use App\Models\ObservationLocation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ObservationLocationService
{
    public function paginate(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return ObservationLocation::query()
            ->withCount('sessionDetails')
            ->search($filters['search'] ?? null)
            ->when($filters['country'] ?? null, fn ($q, $v) => $q->where('country', $v))
            ->when($filters['bortle'] ?? null, function ($q, $v) {
                match ($v) {
                    'dark' => $q->where('light_pollution_level', '<=', 3),
                    'rural' => $q->whereBetween('light_pollution_level', [4, 6]),
                    'urban' => $q->where('light_pollution_level', '>=', 7),
                    default => null,
                };
            })
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function countries(): array
    {
        return ObservationLocation::query()->distinct()->orderBy('country')->pluck('country')->all();
    }

    public function nextCode(): string
    {
        $last = ObservationLocation::where('code', 'like', 'LOC-%')->orderByDesc('code')->value('code');

        return sprintf('LOC-%03d', $last ? ((int) substr($last, -3)) + 1 : 1);
    }

    public function store(array $data): ObservationLocation
    {
        return ObservationLocation::create($data);
    }

    public function update(ObservationLocation $location, array $data): ObservationLocation
    {
        $location->update($data);

        return $location;
    }

    public function canDelete(ObservationLocation $location): bool
    {
        return ! $location->sessionDetails()->exists();
    }

    public function delete(ObservationLocation $location): void
    {
        $location->delete();
    }
}
