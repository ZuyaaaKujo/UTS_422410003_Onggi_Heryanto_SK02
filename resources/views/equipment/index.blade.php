@extends('layouts.app')

@section('title', 'Equipment Inventory')

@section('content')
<x-page-header title="Equipment Inventory" subtitle="Inventaris teleskop, kamera, mount, filter, dan perlengkapan pendukung observatorium." icon="bi-camera-reels">
    <x-slot:actions>
        <a href="{{ route('equipment-categories.index') }}" class="btn btn-ghost"><i class="bi bi-tags me-1"></i>Kategori</a>
        <a href="{{ route('equipment.create') }}" class="btn btn-astra"><i class="bi bi-plus-lg me-1"></i>Tambah Perlengkapan</a>
    </x-slot:actions>
</x-page-header>

<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3"><x-stat-card label="Jenis Perlengkapan" :value="$summary['items']" icon="bi-collection" color="cyan" /></div>
    <div class="col-6 col-lg-3"><x-stat-card label="Tersedia" :value="$summary['available']" icon="bi-check-circle" color="green" /></div>
    <div class="col-6 col-lg-3"><x-stat-card label="Sedang Digunakan" :value="$summary['in_use']" icon="bi-activity" color="blue" /></div>
    <div class="col-6 col-lg-3"><x-stat-card label="Dalam Perawatan" :value="$summary['maintenance']" icon="bi-tools" color="amber" /></div>
</div>

<form method="GET" action="{{ route('equipment.index') }}" class="glass filter-bar mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-lg-3 col-md-6">
            <label class="form-label" for="search">Cari nama / kode / merek / model</label>
            <div class="input-icon"><i class="bi bi-search"></i><input type="search" id="search" name="search" class="form-control" value="{{ $filters['search'] ?? '' }}" placeholder="mis. Celestron"></div>
        </div>
        <div class="col-lg-3 col-md-6">
            <label class="form-label" for="category">Kategori</label>
            <select name="category" id="category" class="form-select">
                <option value="">Semua kategori</option>
                @foreach ($categories as $c)<option value="{{ $c->id }}" @selected(($filters['category'] ?? '') == $c->id)>{{ $c->name }}</option>@endforeach
            </select>
        </div>
        <div class="col-lg-2 col-md-4">
            <label class="form-label" for="condition">Kondisi</label>
            <select name="condition" id="condition" class="form-select">
                <option value="">Semua</option>
                @foreach ($conditions as $v => $l)<option value="{{ $v }}" @selected(($filters['condition'] ?? '') === $v)>{{ $l }}</option>@endforeach
            </select>
        </div>
        <div class="col-lg-2 col-md-4">
            <label class="form-label" for="availability">Ketersediaan</label>
            <select name="availability" id="availability" class="form-select">
                <option value="">Semua</option>
                @foreach ($availabilities as $v => $l)<option value="{{ $v }}" @selected(($filters['availability'] ?? '') === $v)>{{ $l }}</option>@endforeach
            </select>
        </div>
        <div class="col-lg-2 col-md-4 d-flex gap-2">
            <button class="btn btn-astra flex-fill" type="submit"><i class="bi bi-funnel me-1"></i>Filter</button>
            <a href="{{ route('equipment.index') }}" class="btn btn-ghost" title="Reset"><i class="bi bi-x-lg"></i></a>
        </div>
    </div>
</form>

<div class="glass card-pad">
    @if ($items->count())
        <div class="table-responsive">
            <table class="table table-astra table-stack align-middle mb-0">
                <thead><tr><th>Kode</th><th>Perlengkapan</th><th>Kategori</th><th>Merek / Model</th><th>Jumlah</th><th>Kondisi</th><th>Ketersediaan</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td data-label="Kode"><a href="{{ route('equipment.show', $item) }}" class="code-link">{{ $item->code }}</a></td>
                        <td data-label="Perlengkapan"><span class="fw-semibold">{{ $item->name }}</span><small class="d-block text-muted">dipakai di {{ $item->sessions_count }} sesi</small></td>
                        <td data-label="Kategori">{{ $item->category->name }}</td>
                        <td data-label="Merek / Model">{{ trim($item->brand . ' ' . $item->model) ?: '—' }}</td>
                        <td data-label="Jumlah">{{ $item->quantity }}</td>
                        <td data-label="Kondisi"><x-badge :color="$item->condition->color()">{{ $item->condition->label() }}</x-badge></td>
                        <td data-label="Ketersediaan"><x-badge :color="$item->availability_status->color()">{{ $item->availability_status->label() }}</x-badge></td>
                        <td data-label="Aksi" class="text-end text-nowrap">
                            <a href="{{ route('equipment.show', $item) }}" class="btn btn-sm btn-icon-sm" title="Detail" aria-label="Detail {{ $item->name }}"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('equipment.edit', $item) }}" class="btn btn-sm btn-icon-sm" title="Edit" aria-label="Edit {{ $item->name }}"><i class="bi bi-pencil"></i></a>
                            <x-delete-button :url="route('equipment.destroy', $item)" :name="$item->name" />
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="table-footer">
            <small class="text-muted">Menampilkan {{ $items->firstItem() }}–{{ $items->lastItem() }} dari {{ $items->total() }} perlengkapan</small>
            {{ $items->links() }}
        </div>
    @else
        <x-empty-state title="Perlengkapan tidak ditemukan" text="Ubah pencarian/filter atau tambahkan perlengkapan baru."><a href="{{ route('equipment.create') }}" class="btn btn-astra btn-sm">Tambah Perlengkapan</a></x-empty-state>
    @endif
</div>
@endsection
