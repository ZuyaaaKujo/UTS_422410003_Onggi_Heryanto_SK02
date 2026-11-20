<?php

namespace App\Services;

use App\Enums\ObjectCategory;
use App\Enums\SessionStatus;
use App\Models\AstronomicalObject;
use App\Models\Equipment;
use App\Models\ObservationLocation;
use App\Models\ObservationSession;
use Carbon\Carbon;

class DashboardService
{
    public function stats(): array
    {
        return [
            'sessions' => ObservationSession::count(),
            'objects' => AstronomicalObject::count(),
            'locations' => ObservationLocation::count(),
            'equipment' => Equipment::count(),
            'active' => ObservationSession::where('status', SessionStatus::Active->value)->count(),
            'completed' => ObservationSession::where('status', SessionStatus::Completed->value)->count(),
        ];
    }

    /** Jumlah sesi per bulan: 6 bulan terakhir hingga 2 bulan ke depan. */
    public function monthlyActivity(): array
    {
        $start = now()->startOfMonth()->subMonths(6);
        $end = now()->endOfMonth()->addMonths(2);

        $counts = ObservationSession::whereBetween('observation_date', [$start, $end])
            ->get(['observation_date', 'status'])
            ->groupBy(fn ($s) => $s->observation_date->format('Y-m'));

        $labels = $total = $completed = [];
        for ($m = $start->copy(); $m <= $end; $m->addMonth()) {
            $group = $counts->get($m->format('Y-m'), collect());
            $labels[] = $m->translatedFormat('M Y');
            $total[] = $group->count();
            $completed[] = $group->filter(fn ($s) => $s->status === SessionStatus::Completed)->count();
        }

        return compact('labels', 'total', 'completed');
    }

    /** Distribusi sesi berdasarkan kategori objek sasaran. */
    public function categoryDistribution(): array
    {
        $rows = ObservationSession::with('astronomicalObject:id,category')->get()
            ->groupBy(fn ($s) => $s->astronomicalObject->category->value)
            ->map->count();

        $labels = $values = [];
        foreach (ObjectCategory::cases() as $category) {
            if (($n = $rows->get($category->value, 0)) > 0) {
                $labels[] = $category->label();
                $values[] = $n;
            }
        }

        return compact('labels', 'values');
    }

    public function recentSessions(int $limit = 6)
    {
        return ObservationSession::with(['astronomicalObject', 'locationDetail.observationLocation'])
            ->orderByDesc('observation_date')->orderByDesc('start_time')->limit($limit)->get();
    }

    public function upcomingSessions(int $limit = 5)
    {
        return ObservationSession::with(['astronomicalObject', 'locationDetail.observationLocation'])
            ->whereIn('status', [SessionStatus::Scheduled->value, SessionStatus::Active->value])
            ->whereDate('observation_date', '>=', Carbon::today())
            ->orderBy('observation_date')->orderBy('start_time')->limit($limit)->get();
    }

    /** Objek sorotan berganti setiap hari secara deterministik. */
    public function spotlight(): ?AstronomicalObject
    {
        $ids = AstronomicalObject::orderBy('id')->pluck('id');
        if ($ids->isEmpty()) {
            return null;
        }

        return AstronomicalObject::withCount('sessions')->find($ids[now()->dayOfYear % $ids->count()]);
    }

    public function equipmentOverview(): array
    {
        return [
            'total' => Equipment::count(),
            'units' => (int) Equipment::sum('quantity'),
            'available' => Equipment::where('availability_status', 'available')->count(),
            'in_use' => Equipment::where('availability_status', 'in_use')->count(),
            'maintenance' => Equipment::where('availability_status', 'maintenance')->count(),
        ];
    }
}
