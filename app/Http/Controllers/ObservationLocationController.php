<?php

namespace App\Http\Controllers;

use App\Http\Requests\ObservationLocationRequest;
use App\Models\ObservationLocation;
use App\Services\ObservationLocationService;
use Illuminate\Http\Request;

class ObservationLocationController extends Controller
{
    public function __construct(private ObservationLocationService $service) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'country', 'bortle']);

        return view('locations.index', [
            'locations' => $this->service->paginate($filters),
            'countries' => $this->service->countries(),
            'filters' => $filters,
            'mapLocations' => ObservationLocation::get(['id', 'name', 'city', 'latitude', 'longitude', 'light_pollution_level']),
        ]);
    }

    public function create()
    {
        return view('locations.create', [
            'location' => new ObservationLocation(['code' => $this->service->nextCode(), 'light_pollution_level' => 4, 'elevation' => 0]),
            'skyConditions' => ObservationLocation::SKY_CONDITIONS,
        ]);
    }

    public function store(ObservationLocationRequest $request)
    {
        $location = $this->service->store($request->validated());

        return redirect()->route('locations.show', $location)->with('success', 'Lokasi pengamatan berhasil ditambahkan.');
    }

    public function show(ObservationLocation $location)
    {
        $location->load(['sessionDetails.observationSession.astronomicalObject']);

        return view('locations.show', compact('location'));
    }

    public function edit(ObservationLocation $location)
    {
        return view('locations.edit', [
            'location' => $location,
            'skyConditions' => ObservationLocation::SKY_CONDITIONS,
        ]);
    }

    public function update(ObservationLocationRequest $request, ObservationLocation $location)
    {
        $this->service->update($location, $request->validated());

        return redirect()->route('locations.show', $location)->with('success', 'Lokasi pengamatan berhasil diperbarui.');
    }

    public function destroy(ObservationLocation $location)
    {
        if (! $this->service->canDelete($location)) {
            return back()->with('error', "Lokasi \"{$location->name}\" masih dipakai oleh sesi pengamatan sehingga tidak dapat dihapus.");
        }

        $this->service->delete($location);

        return redirect()->route('locations.index')->with('success', 'Lokasi pengamatan berhasil dihapus.');
    }
}
