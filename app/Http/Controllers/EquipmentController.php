<?php

namespace App\Http\Controllers;

use App\Enums\EquipmentAvailability;
use App\Enums\EquipmentCondition;
use App\Http\Requests\EquipmentRequest;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Services\EquipmentService;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function __construct(private EquipmentService $service) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category', 'condition', 'availability']);

        return view('equipment.index', [
            'items' => $this->service->paginate($filters),
            'summary' => $this->service->summary(),
            'categories' => EquipmentCategory::orderBy('name')->get(['id', 'name']),
            'conditions' => EquipmentCondition::options(),
            'availabilities' => EquipmentAvailability::options(),
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        return view('equipment.create', $this->formData(new Equipment([
            'code' => $this->service->nextCode(), 'quantity' => 1,
            'condition' => EquipmentCondition::Good, 'availability_status' => EquipmentAvailability::Available,
        ])));
    }

    public function store(EquipmentRequest $request)
    {
        $equipment = $this->service->store($request->validated());

        return redirect()->route('equipment.show', $equipment)->with('success', 'Perlengkapan berhasil ditambahkan.');
    }

    public function show(Equipment $equipment)
    {
        $equipment->load(['category', 'sessions' => fn ($q) => $q->with('astronomicalObject')->orderByDesc('observation_date')]);

        return view('equipment.show', compact('equipment'));
    }

    public function edit(Equipment $equipment)
    {
        return view('equipment.edit', $this->formData($equipment));
    }

    public function update(EquipmentRequest $request, Equipment $equipment)
    {
        $this->service->update($equipment, $request->validated());

        return redirect()->route('equipment.show', $equipment)->with('success', 'Perlengkapan berhasil diperbarui.');
    }

    public function destroy(Equipment $equipment)
    {
        if (! $this->service->canDelete($equipment)) {
            return back()->with('error', "Perlengkapan \"{$equipment->name}\" tercatat pada riwayat sesi sehingga tidak dapat dihapus.");
        }

        $this->service->delete($equipment);

        return redirect()->route('equipment.index')->with('success', 'Perlengkapan berhasil dihapus.');
    }

    private function formData(Equipment $equipment): array
    {
        return [
            'equipment' => $equipment,
            'categories' => EquipmentCategory::orderBy('name')->get(['id', 'name']),
            'conditions' => EquipmentCondition::options(),
            'availabilities' => EquipmentAvailability::options(),
        ];
    }
}
