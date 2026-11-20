<!DOCTYPE html>
<html lang="id" data-bs-theme="dark" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — ASTRA OBSERVATORY</title>
    <link rel="icon" href="{{ asset('assets/images/logo.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <script>
        // Terapkan tema tersimpan sebelum render agar tidak berkedip.
        (function () {
            try {
                if (localStorage.getItem('astra-theme') === 'light') {
                    document.documentElement.setAttribute('data-bs-theme', 'light');
                    document.documentElement.setAttribute('data-theme', 'light');
                }
            } catch (e) {}
        })();
    </script>
    @stack('styles')
</head>
<body>
    <div class="space-bg" aria-hidden="true"></div>
    <div id="page-loader" class="page-loader" aria-hidden="true"><div class="loader-orbit"><span></span></div></div>

    <div class="app-shell">
        @include('partials.sidebar')

        <div class="app-main">
            @include('partials.topbar')

            <main class="app-content">
                @yield('content')
            </main>

            <footer class="app-footer">
                <span>&copy; {{ now()->year }} ASTRA OBSERVATORY — Dark Sky Observation Management System</span>
                <span class="d-none d-md-inline">UTS Perancangan &amp; Pemrograman Web Lanjut · SK02</span>
            </footer>
        </div>
    </div>

    @include('partials.flash')
    <x-delete-modal />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
