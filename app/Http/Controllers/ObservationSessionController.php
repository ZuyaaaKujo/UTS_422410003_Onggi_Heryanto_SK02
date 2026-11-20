<?php

namespace App\Http\Controllers;

use App\Enums\SessionStatus;
use App\Http\Requests\ObservationSessionRequest;
use App\Models\AstronomicalObject;
use App\Models\Equipment;
use App\Models\ObservationLocation;
use App\Models\ObservationSession;
use App\Models\SessionLocationDetail;
use App\Services\ObservationSessionService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class ObservationSessionController extends Controller
{
    public function __construct(private ObservationSessionService $service) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'object', 'status', 'date']);

        return view('sessions.index', [
            'sessions' => $this->service->paginate($filters),
            'objects' => AstronomicalObject::orderBy('name')->get(['id', 'name']),
            'statuses' => SessionStatus::options(),
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        return view('sessions.create', $this->formData(new ObservationSession([
            'code' => $this->service->nextCode(),
            'status' => SessionStatus::Scheduled,
            'observation_date' => now()->addDay(),
        ])));
    }

    public function store(ObservationSessionRequest $request)
    {
        try {
            $session = $this->service->create(
                $request->sessionData(),
                $request->locationData(),
                $request->selectedEquipment()
            );
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Sesi pengamatan gagal disimpan. Silakan coba lagi.');
        }

        return redirect()->route('sessions.show', $session)->with('success', 'Sesi pengamatan berhasil ditambahkan.');
    }

    public function show(ObservationSession $session)
    {
        $session->load(['astronomicalObject', 'locationDetail.observationLocation', 'equipment.category']);

        return view('sessions.show', compact('session'));
    }

    public function edit(ObservationSession $session)
    {
        $session->load(['locationDetail', 'equipment']);

        return view('sessions.edit', $this->formData($session));
    }

    public function update(ObservationSessionRequest $request, ObservationSession $session)
    {
        try {
            $this->service->update(
                $session,
                $request->sessionData(),
                $request->locationData(),
                $request->selectedEquipment()
            );
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Sesi pengamatan gagal diperbarui. Silakan coba lagi.');
        }

        return redirect()->route('sessions.show', $session)->with('success', 'Sesi pengamatan berhasil diperbarui.');
    }

    public function destroy(ObservationSession $session)
    {
        $name = $session->name;
        $this->service->delete($session);

        return redirect()->route('sessions.index')->with('success', "Sesi \"{$name}\" berhasil dihapus.");
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->only(['search', 'object', 'status', 'date']);

        return response()->streamDownload(function () use ($filters) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar terbaca benar di Excel
            foreach ($this->service->exportRows($filters) as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, 'sesi-pengamatan-' . now()->format('Ymd-His') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function formData(ObservationSession $session): array
    {
        $selected = $session->exists ? $session->equipment->keyBy('id') : collect();

        return [
            'session' => $session,
            'objects' => AstronomicalObject::orderBy('name')->get(['id', 'name', 'code']),
            'locations' => ObservationLocation::orderBy('name')->get(['id', 'name', 'city', 'light_pollution_level']),
            'equipmentList' => Equipment::with('category')->orderBy('equipment_category_id')->orderBy('name')->get(),
            'selectedEquipment' => $selected,
            'statuses' => SessionStatus::options(),
            'weatherOptions' => SessionLocationDetail::WEATHER,
            'visibilityOptions' => SessionLocationDetail::VISIBILITY,
        ];
    }
}
