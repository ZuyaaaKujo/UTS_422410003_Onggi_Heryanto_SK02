@extends('layouts.app')

@section('title', 'Observation Locations')

@push('styles')<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">@endpush

@section('content')
<x-page-header title="Observation Locations" subtitle="Daftar lokasi pengamatan beserta koordinat, elevasi, dan tingkat polusi cahaya (skala Bortle)." icon="bi-geo-alt">
    <x-slot:actions>
        <a href="{{ route('locations.create') }}" class="btn btn-astra"><i class="bi bi-plus-lg me-1"></i>Tambah Lokasi</a>
    </x-slot:actions>
</x-page-header>

<div class="glass card-pad mb-3">
    <div class="card-title-row"><h2><i class="bi bi-globe-asia-australia"></i> Peta Lokasi</h2><small class="text-muted">Warna penanda menunjukkan tingkat polusi cahaya</small></div>
    @php
        $points = $mapLocations->map(fn ($l) => ['id' => $l->id, 'name' => $l->name, 'city' => $l->city, 'lat' => $l->latitude, 'lng' => $l->longitude, 'bortle' => $l->light_pollution_level, 'url' => route('locations.show', $l)])->values();
    @endphp
    <div id="locationsMap" class="map-box" data-points="{{ json_encode($points) }}"></div>
</div>

<form method="GET" action="{{ route('locations.index') }}" class="glass filter-bar mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-md-5">
            <label class="form-label" for="search">Cari nama / kode / kota / provinsi</label>
            <div class="input-icon"><i class="bi bi-search"></i><input type="search" id="search" name="search" class="form-control" value="{{ $filters['search'] ?? '' }}" placeholder="mis. Lembang"></div>
        </div>
        <div class="col-md-3">
            <label class="form-label" for="country">Negara</label>
            <select name="country" id="country" class="form-select">
                <option value="">Semua negara</option>
                @foreach ($countries as $c)<option value="{{ $c }}" @selected(($filters['country'] ?? '') === $c)>{{ $c }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label" for="bortle">Polusi cahaya</label>
            <select name="bortle" id="bortle" class="form-select">
                <option value="">Semua</option>
                <option value="dark" @selected(($filters['bortle'] ?? '') === 'dark')>Gelap (1–3)</option>
                <option value="rural" @selected(($filters['bortle'] ?? '') === 'rural')>Sedang (4–6)</option>
                <option value="urban" @selected(($filters['bortle'] ?? '') === 'urban')>Terang (7–9)</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button class="btn btn-astra flex-fill" type="submit"><i class="bi bi-funnel me-1"></i>Filter</button>
            <a href="{{ route('locations.index') }}" class="btn btn-ghost" title="Reset"><i class="bi bi-x-lg"></i></a>
        </div>
    </div>
</form>

<div class="glass card-pad">
    @if ($locations->count())
        <div class="table-responsive">
            <table class="table table-astra table-stack align-middle mb-0">
                <thead><tr><th>Kode</th><th>Nama Lokasi</th><th>Wilayah</th><th>Koordinat</th><th>Elevasi</th><th>Polusi Cahaya</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @foreach ($locations as $location)
                    <tr>
                        <td data-label="Kode"><a href="{{ route('locations.show', $location) }}" class="code-link">{{ $location->code }}</a></td>
                        <td data-label="Nama"><span class="fw-semibold">{{ $location->name }}</span><small class="d-block text-muted">{{ $location->sky_condition ?: '—' }} · {{ $location->session_details_count }} sesi</small></td>
                        <td data-label="Wilayah">{{ collect([$location->city, $location->province])->filter()->implode(', ') }}<small class="d-block text-muted">{{ $location->country }}</small></td>
                        <td data-label="Koordinat"><small>{{ number_format($location->latitude, 4) }}, {{ number_format($location->longitude, 4) }}</small></td>
                        <td data-label="Elevasi">{{ number_format($location->elevation) }} m</td>
                        <td data-label="Polusi Cahaya"><x-badge :color="$location->bortle_color">Bortle {{ $location->light_pollution_level }}</x-badge></td>
                        <td data-label="Aksi" class="text-end text-nowrap">
                            <a href="{{ route('locations.show', $location) }}" class="btn btn-sm btn-icon-sm" title="Detail" aria-label="Detail {{ $location->name }}"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('locations.edit', $location) }}" class="btn btn-sm btn-icon-sm" title="Edit" aria-label="Edit {{ $location->name }}"><i class="bi bi-pencil"></i></a>
                            <x-delete-button :url="route('locations.destroy', $location)" :name="$location->name" />
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="table-footer">
            <small class="text-muted">Menampilkan {{ $locations->firstItem() }}–{{ $locations->lastItem() }} dari {{ $locations->total() }} lokasi</small>
            {{ $locations->links() }}
        </div>
    @else
        <x-empty-state icon="bi-geo" title="Lokasi tidak ditemukan" text="Ubah pencarian atau tambahkan lokasi baru."><a href="{{ route('locations.create') }}" class="btn btn-astra btn-sm">Tambah Lokasi</a></x-empty-state>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('assets/js/maps.js') }}"></script>
@endpush
