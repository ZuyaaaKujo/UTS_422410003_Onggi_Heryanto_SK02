@extends('layouts.app')

@section('title', 'Observation Sessions')

@section('content')
<x-page-header title="Observation Sessions" subtitle="Kelola sesi pengamatan astronomi: jadwal, objek sasaran, lokasi, dan perlengkapan." icon="bi-binoculars">
    <x-slot:actions>
        <a href="{{ route('sessions.export', $filters) }}" class="btn btn-ghost"><i class="bi bi-filetype-csv me-1"></i>Ekspor CSV</a>
        <a href="{{ route('sessions.create') }}" class="btn btn-astra"><i class="bi bi-plus-lg me-1"></i>Tambah Sesi</a>
    </x-slot:actions>
</x-page-header>

<form method="GET" action="{{ route('sessions.index') }}" class="glass filter-bar mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-lg-3 col-md-6">
            <label class="form-label" for="search">Cari kode / nama sesi</label>
            <div class="input-icon"><i class="bi bi-search"></i><input type="search" id="search" name="search" class="form-control" value="{{ $filters['search'] ?? '' }}" placeholder="mis. OBS-2026 atau Jupiter"></div>
        </div>
        <div class="col-lg-3 col-md-6">
            <label class="form-label" for="object">Objek sasaran</label>
            <select name="object" id="object" class="form-select">
                <option value="">Semua objek</option>
                @foreach ($objects as $o)<option value="{{ $o->id }}" @selected(($filters['object'] ?? '') == $o->id)>{{ $o->name }}</option>@endforeach
            </select>
        </div>
        <div class="col-lg-2 col-md-4">
            <label class="form-label" for="status">Status</label>
            <select name="status" id="status" class="form-select">
                <option value="">Semua status</option>
                @foreach ($statuses as $value => $label)<option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>@endforeach
            </select>
        </div>
        <div class="col-lg-2 col-md-4">
            <label class="form-label" for="date">Tanggal</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ $filters['date'] ?? '' }}">
        </div>
        <div class="col-lg-2 col-md-4 d-flex gap-2">
            <button class="btn btn-astra flex-fill" type="submit"><i class="bi bi-funnel me-1"></i>Filter</button>
            <a href="{{ route('sessions.index') }}" class="btn btn-ghost" title="Reset filter"><i class="bi bi-x-lg"></i></a>
        </div>
    </div>
</form>

<div class="glass card-pad">
    @if ($sessions->count())
        <div class="table-responsive">
            <table class="table table-astra table-stack align-middle mb-0">
                <thead>
                    <tr><th>Kode</th><th>Nama Sesi</th><th>Objek Sasaran</th><th>Tanggal &amp; Waktu</th><th>Lokasi</th><th>Status</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                @foreach ($sessions as $session)
                    <tr>
                        <td data-label="Kode"><a href="{{ route('sessions.show', $session) }}" class="code-link">{{ $session->code }}</a></td>
                        <td data-label="Nama Sesi">
                            <span class="fw-semibold">{{ $session->name }}</span>
                            <small class="d-block text-muted"><i class="bi bi-camera-reels"></i> {{ $session->equipment_count }} perlengkapan</small>
                        </td>
                        <td data-label="Objek">{{ $session->astronomicalObject->name }}</td>
                        <td data-label="Jadwal">{{ $session->observation_date->translatedFormat('d M Y') }}<small class="d-block text-muted">{{ $session->start_time_short }}–{{ $session->end_time_short }}</small></td>
                        <td data-label="Lokasi">{{ $session->locationDetail?->observationLocation?->name ?? '—' }}</td>
                        <td data-label="Status"><x-badge :color="$session->status->color()">{{ $session->status->label() }}</x-badge></td>
                        <td data-label="Aksi" class="text-end text-nowrap">
                            <a href="{{ route('sessions.show', $session) }}" class="btn btn-sm btn-icon-sm" title="Detail" aria-label="Detail {{ $session->name }}"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('sessions.edit', $session) }}" class="btn btn-sm btn-icon-sm" title="Edit" aria-label="Edit {{ $session->name }}"><i class="bi bi-pencil"></i></a>
                            <x-delete-button :url="route('sessions.destroy', $session)" :name="$session->name" />
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="table-footer">
            <small class="text-muted">Menampilkan {{ $sessions->firstItem() }}–{{ $sessions->lastItem() }} dari {{ $sessions->total() }} sesi</small>
            {{ $sessions->links() }}
        </div>
    @else
        <x-empty-state title="Tidak ada sesi ditemukan" text="Coba ubah kata kunci atau filter, atau tambahkan sesi baru.">
            <a href="{{ route('sessions.create') }}" class="btn btn-astra btn-sm">Tambah Sesi</a>
        </x-empty-state>
    @endif
</div>
@endsection
