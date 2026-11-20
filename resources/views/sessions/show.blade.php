@extends('layouts.app')

@section('title', $session->code)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sessions.index') }}">Sessions</a></li>
    <li class="breadcrumb-item active">{{ $session->code }}</li>
@endsection

@section('content')
@php $detail = $session->locationDetail; $location = $detail?->observationLocation; @endphp

<x-page-header :title="$session->name" :subtitle="'Kode sesi ' . $session->code" icon="bi-binoculars">
    <x-slot:actions>
        <a href="{{ route('sessions.index') }}" class="btn btn-ghost"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        <a href="{{ route('sessions.edit', $session) }}" class="btn btn-astra"><i class="bi bi-pencil me-1"></i>Edit</a>
        <button type="button" class="btn btn-outline-danger" data-delete-url="{{ route('sessions.destroy', $session) }}" data-delete-name="{{ $session->name }}"><i class="bi bi-trash me-1"></i>Hapus</button>
    </x-slot:actions>
</x-page-header>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="glass card-pad mb-3">
            <div class="card-title-row">
                <h2><i class="bi bi-info-circle"></i> Informasi Sesi</h2>
                <x-badge :color="$session->status->color()">{{ $session->status->label() }}</x-badge>
            </div>
            <dl class="detail-grid">
                <div><dt>Objek Sasaran</dt><dd><a href="{{ route('astronomical-objects.show', $session->astronomicalObject) }}" class="link-astra">{{ $session->astronomicalObject->name }}</a> <x-badge :color="$session->astronomicalObject->category->color()">{{ $session->astronomicalObject->category->label() }}</x-badge></dd></div>
                <div><dt>Tanggal</dt><dd>{{ $session->observation_date->translatedFormat('l, d F Y') }}</dd></div>
                <div><dt>Waktu</dt><dd>{{ $session->start_time_short }} – {{ $session->end_time_short }} WIB</dd></div>
                <div><dt>Dibuat</dt><dd>{{ $session->created_at->translatedFormat('d M Y H:i') }}</dd></div>
            </dl>
            <h6 class="mt-3">Deskripsi Pengamatan</h6>
            <p class="text-muted">{{ $session->description ?: 'Tidak ada deskripsi.' }}</p>
            <h6>Catatan Tambahan</h6>
            <p class="text-muted mb-0">{{ $session->notes ?: 'Tidak ada catatan.' }}</p>
        </div>

        <div class="glass card-pad mb-3">
            <div class="card-title-row"><h2><i class="bi bi-camera-reels"></i> Perlengkapan yang Digunakan</h2><small class="text-muted">{{ $session->equipment->count() }} item</small></div>
            @if ($session->equipment->count())
                <div class="table-responsive">
                    <table class="table table-astra table-stack align-middle mb-0">
                        <thead><tr><th>Kode</th><th>Perlengkapan</th><th>Kategori</th><th>Jumlah</th><th>Catatan Penggunaan</th></tr></thead>
                        <tbody>
                        @foreach ($session->equipment as $item)
                            <tr>
                                <td data-label="Kode"><a class="code-link" href="{{ route('equipment.show', $item) }}">{{ $item->code }}</a></td>
                                <td data-label="Perlengkapan">{{ $item->name }}<small class="d-block text-muted">{{ trim($item->brand . ' ' . $item->model) }}</small></td>
                                <td data-label="Kategori">{{ $item->category->name }}</td>
                                <td data-label="Jumlah">{{ $item->pivot->quantity_used }}</td>
                                <td data-label="Catatan">{{ $item->pivot->usage_notes ?: '—' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <x-empty-state icon="bi-camera-reels" title="Belum ada perlengkapan" text="Sesi ini tidak memakai perlengkapan tercatat." />
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        <div class="glass card-pad mb-3">
            <div class="card-title-row"><h2><i class="bi bi-geo-alt"></i> Detail Lokasi</h2></div>
            @if ($detail)
                <div class="mb-3">
                    <a href="{{ route('locations.show', $location) }}" class="link-astra fw-semibold">{{ $location->name }}</a>
                    <small class="d-block text-muted">{{ collect([$location->city, $location->province, $location->country])->filter()->implode(', ') }}</small>
                </div>
                <dl class="detail-list">
                    <div><dt><i class="bi bi-cloud-sun"></i> Cuaca</dt><dd>{{ $detail->weather_condition }}</dd></div>
                    <div><dt><i class="bi bi-eye"></i> Visibilitas</dt><dd>{{ $detail->visibility_condition }}</dd></div>
                    <div><dt><i class="bi bi-thermometer-half"></i> Suhu</dt><dd>{{ $detail->temperature !== null ? $detail->temperature . ' °C' : '—' }}</dd></div>
                    <div><dt><i class="bi bi-droplet"></i> Kelembapan</dt><dd>{{ $detail->humidity !== null ? $detail->humidity . ' %' : '—' }}</dd></div>
                    <div><dt><i class="bi bi-lightbulb"></i> Polusi Cahaya</dt><dd>{{ $detail->light_pollution_level ? 'Bortle ' . $detail->light_pollution_level : '—' }}</dd></div>
                </dl>
                <h6 class="mt-3">Catatan Lingkungan</h6>
                <p class="text-muted small mb-0">{{ $detail->environment_notes ?: 'Tidak ada catatan.' }}</p>
            @else
                <x-empty-state icon="bi-geo" title="Detail lokasi belum diisi" />
            @endif
        </div>

        <div class="glass card-pad">
            <div class="card-title-row"><h2><i class="bi bi-signpost-split"></i> Timeline</h2></div>
            <ul class="timeline">
                <li><i class="bi bi-plus-circle"></i><div><strong>Sesi dibuat</strong><small>{{ $session->created_at->translatedFormat('d M Y, H:i') }}</small></div></li>
                <li><i class="bi bi-calendar-event"></i><div><strong>Jadwal pengamatan</strong><small>{{ $session->observation_date->translatedFormat('d M Y') }}, {{ $session->start_time_short }}–{{ $session->end_time_short }}</small></div></li>
                <li class="tl-{{ $session->status->color() }}"><i class="bi bi-flag"></i><div><strong>Status: {{ $session->status->label() }}</strong><small>Diperbarui {{ $session->updated_at->diffForHumans() }}</small></div></li>
            </ul>
        </div>
    </div>
</div>
@endsection
