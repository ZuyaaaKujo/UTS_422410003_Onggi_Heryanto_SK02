@php
    $nav = [
        ['route' => 'dashboard', 'match' => 'dashboard', 'icon' => 'bi-speedometer2', 'label' => 'Dashboard'],
        ['route' => 'sessions.index', 'match' => 'sessions.*', 'icon' => 'bi-binoculars', 'label' => 'Observation Sessions'],
        ['route' => 'astronomical-objects.index', 'match' => 'astronomical-objects.*', 'icon' => 'bi-stars', 'label' => 'Astronomical Objects'],
        ['route' => 'locations.index', 'match' => 'locations.*', 'icon' => 'bi-geo-alt', 'label' => 'Observation Locations'],
        ['route' => 'equipment.index', 'match' => 'equipment.*', 'icon' => 'bi-camera-reels', 'label' => 'Equipment Inventory'],
        ['route' => 'equipment-categories.index', 'match' => 'equipment-categories.*', 'icon' => 'bi-tags', 'label' => 'Equipment Categories'],
    ];
@endphp

<div class="offcanvas-lg offcanvas-start sidebar" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
    <div class="sidebar-inner">
        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ route('dashboard') }}" class="brand" id="sidebarLabel">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="" width="38" height="38">
                <span><strong>ASTRA</strong><small>OBSERVATORY</small></span>
            </a>
            <button type="button" class="btn-close d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#sidebar" aria-label="Tutup"></button>
        </div>

        <nav class="nav flex-column mt-4">
            <div class="nav-caption">Navigasi</div>
            @foreach ($nav as $item)
                <a class="nav-link {{ request()->routeIs($item['match']) ? 'active' : '' }}" href="{{ route($item['route']) }}">
                    <i class="bi {{ $item['icon'] }}"></i><span>{{ $item['label'] }}</span>
                </a>
            @endforeach

            <div class="nav-caption mt-4">Aksi Cepat</div>
            <a class="nav-link" href="{{ route('sessions.create') }}"><i class="bi bi-plus-circle"></i><span>Sesi Baru</span></a>
            <a class="nav-link" href="{{ route('sessions.export') }}"><i class="bi bi-filetype-csv"></i><span>Ekspor Sesi (CSV)</span></a>
        </nav>

        <div class="sidebar-card mt-auto">
            <i class="bi bi-moon-stars"></i>
            <div>
                <strong>Dark Sky Mode</strong>
                <small>Tema gelap menjaga adaptasi mata saat mengamati.</small>
            </div>
        </div>
    </div>
</div>
