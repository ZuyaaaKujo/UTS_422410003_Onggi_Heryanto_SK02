@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $hour = now()->hour;
    $greeting = match (true) { $hour < 11 => 'Selamat pagi', $hour < 15 => 'Selamat siang', $hour < 18 => 'Selamat sore', default => 'Selamat malam' };
@endphp

{{-- 1. Welcome Section --}}
<section class="hero-card mb-4">
    <div class="hero-content">
        <span class="hero-eyebrow"><i class="bi bi-broadcast"></i> ASTRA OBSERVATORY · Dark Sky Observation Management System</span>
        <h1>{{ $greeting }}, Admin Observatorium</h1>
        <p class="mb-2">{{ now()->translatedFormat('l, d F Y') }} — ringkasan aktivitas observatorium hari ini.</p>
        <p class="hero-summary">
            <strong>{{ $stats['active'] }}</strong> sesi sedang aktif ·
            <strong>{{ $upcomingSessions->where('status', \App\Enums\SessionStatus::Scheduled)->count() }}</strong> sesi terjadwal berikutnya ·
            <strong>{{ $equipmentOverview['available'] }}</strong> perlengkapan siap dipakai
        </p>
        <div class="d-flex flex-wrap gap-2 mt-3">
            <a href="{{ route('sessions.create') }}" class="btn btn-astra"><i class="bi bi-plus-lg me-1"></i>Sesi Baru</a>
            <a href="{{ route('sessions.index') }}" class="btn btn-ghost">Lihat Semua Sesi</a>
        </div>
    </div>
</section>

{{-- 2. Statistik utama --}}
<section class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2"><x-stat-card label="Total Sesi" :value="$stats['sessions']" icon="bi-binoculars" color="cyan" :href="route('sessions.index')" /></div>
    <div class="col-6 col-md-4 col-xl-2"><x-stat-card label="Objek Astronomi" :value="$stats['objects']" icon="bi-stars" color="violet" :href="route('astronomical-objects.index')" /></div>
    <div class="col-6 col-md-4 col-xl-2"><x-stat-card label="Lokasi" :value="$stats['locations']" icon="bi-geo-alt" color="blue" :href="route('locations.index')" /></div>
    <div class="col-6 col-md-4 col-xl-2"><x-stat-card label="Perlengkapan" :value="$stats['equipment']" icon="bi-camera-reels" color="pink" :href="route('equipment.index')" /></div>
    <div class="col-6 col-md-4 col-xl-2"><x-stat-card label="Sesi Aktif" :value="$stats['active']" icon="bi-activity" color="green" :href="route('sessions.index', ['status' => 'active'])" /></div>
    <div class="col-6 col-md-4 col-xl-2"><x-stat-card label="Sesi Selesai" :value="$stats['completed']" icon="bi-check2-circle" color="amber" :href="route('sessions.index', ['status' => 'completed'])" /></div>
</section>

<div class="row g-3 mb-4">
    {{-- 3. Observation Activity Chart --}}
    <div class="col-lg-8">
        <div class="glass card-pad h-100">
            <div class="card-title-row">
                <h2><i class="bi bi-graph-up"></i> Observation Activity</h2>
                <small class="text-muted">Jumlah sesi per bulan (6 bulan terakhir — 2 bulan ke depan)</small>
            </div>
            <div class="chart-box"><canvas id="activityChart" aria-label="Grafik aktivitas pengamatan per bulan" role="img"></canvas></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="glass card-pad h-100">
            <div class="card-title-row">
                <h2><i class="bi bi-pie-chart"></i> Sesi per Kategori Objek</h2>
            </div>
            <div class="chart-box chart-box-sm"><canvas id="categoryChart" aria-label="Distribusi sesi berdasarkan kategori objek" role="img"></canvas></div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- 4. Recent Observation Sessions --}}
    <div class="col-xl-8">
        <div class="glass card-pad h-100">
            <div class="card-title-row">
                <h2><i class="bi bi-clock-history"></i> Recent Observation Sessions</h2>
                <a href="{{ route('sessions.index') }}" class="link-astra">Semua sesi <i class="bi bi-arrow-right"></i></a>
            </div>
            @forelse ($recentSessions as $session)
                @if ($loop->first)
                <div class="table-responsive"><table class="table table-astra table-stack align-middle mb-0">
                    <thead><tr><th>Kode</th><th>Nama Sesi</th><th>Objek</th><th>Lokasi</th><th>Status</th></tr></thead>
                    <tbody>
                @endif
                <tr>
                    <td data-label="Kode"><a href="{{ route('sessions.show', $session) }}" class="code-link">{{ $session->code }}</a></td>
                    <td data-label="Nama Sesi">{{ $session->name }}<small class="d-block text-muted">{{ $session->observation_date->translatedFormat('d M Y') }}</small></td>
                    <td data-label="Objek">{{ $session->astronomicalObject->name }}</td>
                    <td data-label="Lokasi">{{ $session->locationDetail?->observationLocation?->name ?? '—' }}</td>
                    <td data-label="Status"><x-badge :color="$session->status->color()">{{ $session->status->label() }}</x-badge></td>
                </tr>
                @if ($loop->last)</tbody></table></div>@endif
            @empty
                <x-empty-state title="Belum ada sesi" text="Tambahkan sesi pengamatan pertama Anda."><a href="{{ route('sessions.create') }}" class="btn btn-astra btn-sm">Tambah Sesi</a></x-empty-state>
            @endforelse
        </div>
    </div>

    {{-- 5. Astronomy Spotlight --}}
    <div class="col-xl-4">
        <div class="glass spotlight h-100">
            @if ($spotlight)
                <div class="spotlight-img">
                    <img src="{{ $spotlight->image_src }}" alt="{{ $spotlight->name }}" loading="lazy" data-fallback="{{ $spotlight->category->fallbackImage() }}">
                    <span class="spotlight-tag"><i class="bi bi-stars"></i> Astronomy Spotlight</span>
                </div>
                <div class="card-pad">
                    <x-badge :color="$spotlight->category->color()">{{ $spotlight->category->label() }}</x-badge>
                    <h3 class="mt-2 mb-1">{{ $spotlight->name }}</h3>
                    @if ($spotlight->constellation)<small class="text-muted d-block mb-2">Konstelasi {{ $spotlight->constellation }}</small>@endif
                    <p class="text-muted small">{{ \Illuminate\Support\Str::limit($spotlight->description, 170) }}</p>
                    <a href="{{ route('astronomical-objects.show', $spotlight) }}" class="link-astra">Lihat detail objek <i class="bi bi-arrow-right"></i></a>
                </div>
            @else
                <x-empty-state title="Belum ada objek astronomi" />
            @endif
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- 6. Equipment Overview --}}
    <div class="col-lg-4">
        <div class="glass card-pad h-100">
            <div class="card-title-row"><h2><i class="bi bi-camera-reels"></i> Equipment Overview</h2></div>
            <div class="eq-ring">
                <div class="eq-total"><strong>{{ $equipmentOverview['total'] }}</strong><small>jenis · {{ $equipmentOverview['units'] }} unit</small></div>
            </div>
            @php $t = max($equipmentOverview['total'], 1); @endphp
            @foreach ([['Tersedia', 'available', 'green'], ['Sedang digunakan', 'in_use', 'blue'], ['Perawatan', 'maintenance', 'amber']] as [$label, $key, $color])
                <div class="eq-row">
                    <span>{{ $label }}</span><strong>{{ $equipmentOverview[$key] }}</strong>
                    <div class="eq-bar"><i class="bar-{{ $color }}" style="width: {{ round($equipmentOverview[$key] / $t * 100) }}%"></i></div>
                </div>
            @endforeach
            <a href="{{ route('equipment.index') }}" class="link-astra mt-3 d-inline-block">Kelola inventaris <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>

    {{-- 7. Observation Schedule --}}
    <div class="col-lg-4">
        <div class="glass card-pad h-100">
            <div class="card-title-row"><h2><i class="bi bi-calendar3"></i> Observation Schedule</h2></div>
            @forelse ($upcomingSessions as $session)
                <a href="{{ route('sessions.show', $session) }}" class="schedule-item">
                    <div class="schedule-date"><strong>{{ $session->observation_date->format('d') }}</strong><small>{{ $session->observation_date->translatedFormat('M') }}</small></div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ $session->name }}</div>
                        <small class="text-muted">{{ $session->start_time_short }}–{{ $session->end_time_short }} · {{ $session->astronomicalObject->name }}</small>
                    </div>
                    <x-badge :color="$session->status->color()">{{ $session->status->label() }}</x-badge>
                </a>
            @empty
                <x-empty-state icon="bi-calendar-x" title="Tidak ada jadwal mendatang" />
            @endforelse
        </div>
    </div>

    {{-- 8. Quick Actions --}}
    <div class="col-lg-4">
        <div class="glass card-pad h-100">
            <div class="card-title-row"><h2><i class="bi bi-lightning-charge"></i> Quick Actions</h2></div>
            <div class="row g-2">
                @foreach ([
                    ['sessions.create', 'bi-binoculars', 'Tambah Sesi Pengamatan'],
                    ['astronomical-objects.create', 'bi-stars', 'Tambah Objek Sasaran'],
                    ['locations.create', 'bi-geo-alt', 'Tambah Lokasi'],
                    ['equipment.create', 'bi-camera-reels', 'Tambah Perlengkapan'],
                ] as [$route, $icon, $label])
                    <div class="col-6">
                        <a href="{{ route($route) }}" class="quick-action"><i class="bi {{ $icon }}"></i><span>{{ $label }}</span></a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    window.ASTRA_CHARTS = {
        activity: @json($activity),
        distribution: @json($distribution),
    };
</script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
@endpush
