@extends('layouts.app')

@section('title', $location->name)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('locations.index') }}">Locations</a></li>
    <li class="breadcrumb-item active">{{ $location->code }}</li>
@endsection

@push('styles')<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">@endpush

@section('content')
<x-page-header :title="$location->name" :subtitle="'Kode lokasi ' . $location->code" icon="bi-geo-alt">
    <x-slot:actions>
        <a href="{{ route('locations.index') }}" class="btn btn-ghost"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        <a href="{{ route('locations.edit', $location) }}" class="btn btn-astra"><i class="bi bi-pencil me-1"></i>Edit</a>
        <button type="button" class="btn btn-outline-danger" data-delete-url="{{ route('locations.destroy', $location) }}" data-delete-name="{{ $location->name }}"><i class="bi bi-trash me-1"></i>Hapus</button>
    </x-slot:actions>
</x-page-header>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="glass card-pad mb-3">
            <div class="card-title-row"><h2><i class="bi bi-map"></i> Peta</h2>
                <a class="link-astra small" target="_blank" rel="noopener" href="https://www.openstreetmap.org/?mlat={{ $location->latitude }}&mlon={{ $location->longitude }}#map=10/{{ $location->latitude }}/{{ $location->longitude }}">Buka di OpenStreetMap <i class="bi bi-box-arrow-up-right"></i></a>
            </div>
            @php
                $points = [['id' => $location->id, 'name' => $location->name, 'city' => $location->city, 'lat' => $location->latitude, 'lng' => $location->longitude, 'bortle' => $location->light_pollution_level, 'url' => null]];
            @endphp
            <div id="locationsMap" class="map-box" data-points="{{ json_encode($points) }}" data-zoom="9"></div>
        </div>
        <div class="glass card-pad">
            <div class="card-title-row"><h2><i class="bi bi-binoculars"></i> Sesi di Lokasi Ini</h2></div>
            @forelse ($location->sessionDetails->sortByDesc(fn ($d) => $d->observationSession->observation_date) as $detail)
                @php $s = $detail->observationSession; @endphp
                <a href="{{ route('sessions.show', $s) }}" class="schedule-item">
                    <div class="schedule-date"><strong>{{ $s->observation_date->format('d') }}</strong><small>{{ $s->observation_date->translatedFormat('M') }}</small></div>
                    <div class="flex-grow-1"><div class="fw-semibold">{{ $s->name }}</div><small class="text-muted">{{ $s->astronomicalObject->name }} · {{ $detail->weather_condition }}</small></div>
                    <x-badge :color="$s->status->color()">{{ $s->status->label() }}</x-badge>
                </a>
            @empty
                <x-empty-state icon="bi-binoculars" title="Belum ada sesi di lokasi ini" />
            @endforelse
        </div>
    </div>
    <div class="col-lg-5">
        <div class="glass card-pad">
            <div class="card-title-row"><h2><i class="bi bi-info-circle"></i> Informasi Lokasi</h2><x-badge :color="$location->bortle_color">Bortle {{ $location->light_pollution_level }}</x-badge></div>
            <dl class="detail-list">
                <div><dt>Negara</dt><dd>{{ $location->country }}</dd></div>
                <div><dt>Provinsi</dt><dd>{{ $location->province ?: '—' }}</dd></div>
                <div><dt>Kota</dt><dd>{{ $location->city ?: '—' }}</dd></div>
                <div><dt>Latitude</dt><dd>{{ number_format($location->latitude, 6) }}</dd></div>
                <div><dt>Longitude</dt><dd>{{ number_format($location->longitude, 6) }}</dd></div>
                <div><dt>Elevasi</dt><dd>{{ number_format($location->elevation) }} m dpl</dd></div>
                <div><dt>Kondisi langit</dt><dd>{{ $location->sky_condition ?: '—' }}</dd></div>
                <div><dt>Polusi cahaya</dt><dd>{{ $location->bortle_label }}</dd></div>
            </dl>
            <h6 class="mt-3">Deskripsi</h6>
            <p class="text-muted mb-0">{{ $location->description ?: 'Belum ada deskripsi.' }}</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('assets/js/maps.js') }}"></script>
@endpush
