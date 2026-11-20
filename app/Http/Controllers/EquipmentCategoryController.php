<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipmentCategoryRequest;
use App\Models\EquipmentCategory;
use App\Services\EquipmentCategoryService;
use Illuminate\Http\Request;

class EquipmentCategoryController extends Controller
{
    public function __construct(private EquipmentCategoryService $service) {}

    public function index(Request $request)
    {
        return view('equipment-categories.index', [
            'categories' => $this->service->paginate($request->query('search')),
            'filters' => $request->only('search'),
        ]);
    }

    public function create()
    {
        return view('equipment-categories.create', ['category' => new EquipmentCategory()]);
    }

    public function store(EquipmentCategoryRequest $request)
    {
        $this->service->store($request->validated());

        return redirect()->route('equipment-categories.index')->with('success', 'Kategori perlengkapan berhasil ditambahkan.');
    }

    public function edit(EquipmentCategory $equipment_category)
    {
        return view('equipment-categories.edit', ['category' => $equipment_category]);
    }

    public function update(EquipmentCategoryRequest $request, EquipmentCategory $equipment_category)
    {
        $this->service->update($equipment_category, $request->validated());

        return redirect()->route('equipment-categories.index')->with('success', 'Kategori perlengkapan berhasil diperbarui.');
    }

    public function destroy(EquipmentCategory $equipment_category)
    {
        if (! $this->service->canDelete($equipment_category)) {
            return back()->with('error', "Kategori \"{$equipment_category->name}\" masih memiliki perlengkapan sehingga tidak dapat dihapus.");
        }

        $this->service->delete($equipment_category);

        return redirect()->route('equipment-categories.index')->with('success', 'Kategori perlengkapan berhasil dihapus.');
    }
}
