<header class="topbar">
    <button class="btn btn-icon d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar" aria-label="Buka menu">
        <i class="bi bi-list fs-4"></i>
    </button>

    <div class="topbar-title d-none d-md-block">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Astra</a></li>
                @hasSection('breadcrumb')
                    @yield('breadcrumb')
                @else
                    <li class="breadcrumb-item active">@yield('title', 'Dashboard')</li>
                @endif
            </ol>
        </nav>
    </div>

    <form action="{{ route('search') }}" method="GET" class="topbar-search" role="search">
        <i class="bi bi-search"></i>
        <input type="search" name="q" value="{{ request()->routeIs('search') ? request('q') : '' }}" placeholder="Cari sesi, objek, lokasi, perlengkapan…" aria-label="Pencarian global" minlength="2" required>
    </form>

    <div class="topbar-right">
        <div class="clock d-none d-xl-flex" title="Waktu Jakarta (WIB)">
            <i class="bi bi-clock"></i>
            <div>
                <span id="clock-time" data-now="{{ now()->toIso8601String() }}">{{ now()->format('H:i:s') }}</span>
                <small>{{ now()->translatedFormat('l, d M Y') }}</small>
            </div>
        </div>
        <button class="btn btn-icon" id="theme-toggle" type="button" title="Ganti tema" aria-label="Ganti tema gelap/terang">
            <i class="bi bi-brightness-high"></i>
        </button>
        @php $activeCount = \App\Models\ObservationSession::where('status', 'active')->count(); @endphp
        <a href="{{ route('sessions.index', ['status' => 'active']) }}" class="btn btn-icon position-relative" title="Sesi aktif" aria-label="Sesi aktif">
            <i class="bi bi-bell"></i>
            @if ($activeCount > 0)<span class="notif-dot">{{ $activeCount }}</span>@endif
        </a>
        <div class="user-chip d-none d-sm-flex">
            <span class="avatar">AO</span>
            <div><strong>Admin Observatorium</strong><small>Operator Sistem</small></div>
        </div>
    </div>
</header>
