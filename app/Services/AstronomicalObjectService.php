<?php

namespace App\Services;

use App\Models\AstronomicalObject;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AstronomicalObjectService
{
    public function paginate(array $filters, int $perPage = 9): LengthAwarePaginator
    {
        return AstronomicalObject::query()
            ->withCount('sessions')
            ->search($filters['search'] ?? null)
            ->ofCategory($filters['category'] ?? null)
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function nextCode(): string
    {
        $last = AstronomicalObject::where('code', 'like', 'OBJ-%')->orderByDesc('code')->value('code');

        return sprintf('OBJ-%03d', $last ? ((int) substr($last, -3)) + 1 : 1);
    }

    public function store(array $data): AstronomicalObject
    {
        return AstronomicalObject::create($data);
    }

    public function update(AstronomicalObject $object, array $data): AstronomicalObject
    {
        $object->update($data);

        return $object;
    }

    public function canDelete(AstronomicalObject $object): bool
    {
        return ! $object->sessions()->exists();
    }

    public function delete(AstronomicalObject $object): void
    {
        $object->delete();
    }
}
