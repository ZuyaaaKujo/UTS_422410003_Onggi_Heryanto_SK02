<?php

namespace App\Services;

use App\Models\EquipmentCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EquipmentCategoryService
{
    public function paginate(?string $search, int $perPage = 10): LengthAwarePaginator
    {
        return EquipmentCategory::query()
            ->withCount('equipment')
            ->when($search, fn ($q, $v) => $q->where('name', 'like', "%{$v}%"))
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function store(array $data): EquipmentCategory
    {
        return EquipmentCategory::create($data);
    }

    public function update(EquipmentCategory $category, array $data): EquipmentCategory
    {
        $category->update($data);

        return $category;
    }

    public function canDelete(EquipmentCategory $category): bool
    {
        return ! $category->equipment()->exists();
    }

    public function delete(EquipmentCategory $category): void
    {
        $category->delete();
    }
}
