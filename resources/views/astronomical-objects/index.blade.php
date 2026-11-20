@extends('layouts.app')

@section('title', 'Astronomical Objects')

@section('content')
<x-page-header title="Astronomical Objects" subtitle="Katalog objek sasaran pengamatan: planet, bintang, galaksi, nebula, dan fenomena langit." icon="bi-stars">
    <x-slot:actions>
        <a href="{{ route('astronomical-objects.create') }}" class="btn btn-astra"><i class="bi bi-plus-lg me-1"></i>Tambah Objek</a>
    </x-slot:actions>
</x-page-header>

<form method="GET" action="{{ route('astronomical-objects.index') }}" class="glass filter-bar mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-md-6">
            <label class="form-label" for="search">Cari nama / kode / konstelasi</label>
            <div class="input-icon"><i class="bi bi-search"></i><input type="search" id="search" name="search" class="form-control" value="{{ $filters['search'] ?? '' }}" placeholder="mis. Orion"></div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="category">Kategori</label>
            <select name="category" id="category" class="form-select">
                <option value="">Semua kategori</option>
                @foreach ($categories as $value => $label)<option value="{{ $value }}" @selected(($filters['category'] ?? '') === $value)>{{ $label }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button class="btn btn-astra flex-fill" type="submit"><i class="bi bi-funnel me-1"></i>Filter</button>
            <a href="{{ route('astronomical-objects.index') }}" class="btn btn-ghost" title="Reset"><i class="bi bi-x-lg"></i></a>
        </div>
    </div>
</form>

@if ($objects->count())
    <div class="row g-3">
        @foreach ($objects as $object)
            <div class="col-xl-4 col-md-6">
                <article class="glass object-card h-100">
                    <a href="{{ route('astronomical-objects.show', $object) }}" class="object-img">
                        <img src="{{ $object->image_src }}" alt="{{ $object->name }}" loading="lazy" data-fallback="{{ $object->category->fallbackImage() }}">
                        <x-badge :color="$object->category->color()" class="object-badge">{{ $object->category->label() }}</x-badge>
                    </a>
                    <div class="card-pad">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <h3 class="object-name"><a href="{{ route('astronomical-objects.show', $object) }}">{{ $object->name }}</a></h3>
                                <small class="text-muted">{{ $object->code }}@if ($object->constellation) · {{ $object->constellation }}@endif</small>
                            </div>
                            <span class="chip" title="Jumlah sesi"><i class="bi bi-binoculars"></i> {{ $object->sessions_count }}</span>
                        </div>
                        <p class="text-muted small mt-2 mb-3">{{ \Illuminate\Support\Str::limit($object->description, 105) }}</p>
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('astronomical-objects.show', $object) }}" class="btn btn-sm btn-icon-sm" title="Detail" aria-label="Detail {{ $object->name }}"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('astronomical-objects.edit', $object) }}" class="btn btn-sm btn-icon-sm" title="Edit" aria-label="Edit {{ $object->name }}"><i class="bi bi-pencil"></i></a>
                            <x-delete-button :url="route('astronomical-objects.destroy', $object)" :name="$object->name" />
                        </div>
                    </div>
                </article>
            </div>
        @endforeach
    </div>
    <div class="table-footer mt-3">
        <small class="text-muted">Menampilkan {{ $objects->firstItem() }}–{{ $objects->lastItem() }} dari {{ $objects->total() }} objek</small>
        {{ $objects->links() }}
    </div>
@else
    <div class="glass card-pad"><x-empty-state title="Objek tidak ditemukan" text="Ubah pencarian atau tambahkan objek baru."><a href="{{ route('astronomical-objects.create') }}" class="btn btn-astra btn-sm">Tambah Objek</a></x-empty-state></div>
@endif
@endsection
