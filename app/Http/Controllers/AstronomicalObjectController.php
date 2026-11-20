<?php

namespace App\Http\Controllers;

use App\Enums\ObjectCategory;
use App\Http\Requests\AstronomicalObjectRequest;
use App\Models\AstronomicalObject;
use App\Services\AstronomicalObjectService;
use Illuminate\Http\Request;

class AstronomicalObjectController extends Controller
{
    public function __construct(private AstronomicalObjectService $service) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category']);

        return view('astronomical-objects.index', [
            'objects' => $this->service->paginate($filters),
            'categories' => ObjectCategory::options(),
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        return view('astronomical-objects.create', [
            'object' => new AstronomicalObject(['code' => $this->service->nextCode()]),
            'categories' => ObjectCategory::options(),
        ]);
    }

    public function store(AstronomicalObjectRequest $request)
    {
        $object = $this->service->store($request->validated());

        return redirect()->route('astronomical-objects.show', $object)->with('success', 'Objek astronomi berhasil ditambahkan.');
    }

    public function show(AstronomicalObject $astronomical_object)
    {
        $astronomical_object->load(['sessions' => fn ($q) => $q->orderByDesc('observation_date')->limit(10)]);

        return view('astronomical-objects.show', ['object' => $astronomical_object]);
    }

    public function edit(AstronomicalObject $astronomical_object)
    {
        return view('astronomical-objects.edit', [
            'object' => $astronomical_object,
            'categories' => ObjectCategory::options(),
        ]);
    }

    public function update(AstronomicalObjectRequest $request, AstronomicalObject $astronomical_object)
    {
        $this->service->update($astronomical_object, $request->validated());

        return redirect()->route('astronomical-objects.show', $astronomical_object)->with('success', 'Objek astronomi berhasil diperbarui.');
    }

    public function destroy(AstronomicalObject $astronomical_object)
    {
        if (! $this->service->canDelete($astronomical_object)) {
            return back()->with('error', "Objek \"{$astronomical_object->name}\" masih dipakai oleh sesi pengamatan sehingga tidak dapat dihapus.");
        }

        $this->service->delete($astronomical_object);

        return redirect()->route('astronomical-objects.index')->with('success', 'Objek astronomi berhasil dihapus.');
    }
}
