@extends('layouts.app')

@section('title', 'Equipment Categories')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('equipment.index') }}">Equipment</a></li>
    <li class="breadcrumb-item active">Categories</li>
@endsection

@section('content')
<x-page-header title="Equipment Categories" subtitle="Kelompokkan perlengkapan astronomi ke dalam kategori." icon="bi-tags">
    <x-slot:actions>
        <a href="{{ route('equipment-categories.create') }}" class="btn btn-astra"><i class="bi bi-plus-lg me-1"></i>Tambah Kategori</a>
    </x-slot:actions>
</x-page-header>

<form method="GET" action="{{ route('equipment-categories.index') }}" class="glass filter-bar mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-md-8">
            <label class="form-label" for="search">Cari nama kategori</label>
            <div class="input-icon"><i class="bi bi-search"></i><input type="search" id="search" name="search" class="form-control" value="{{ $filters['search'] ?? '' }}" placeholder="mis. Filter"></div>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button class="btn btn-astra flex-fill" type="submit"><i class="bi bi-search me-1"></i>Cari</button>
            <a href="{{ route('equipment-categories.index') }}" class="btn btn-ghost" title="Reset"><i class="bi bi-x-lg"></i></a>
        </div>
    </div>
</form>

<div class="glass card-pad">
    @if ($categories->count())
        <div class="table-responsive">
            <table class="table table-astra table-stack align-middle mb-0">
                <thead><tr><th>#</th><th>Nama Kategori</th><th>Deskripsi</th><th>Perlengkapan</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td data-label="#">{{ $categories->firstItem() + $loop->index }}</td>
                        <td data-label="Nama"><a class="fw-semibold link-astra" href="{{ route('equipment.index', ['category' => $category->id]) }}">{{ $category->name }}</a></td>
                        <td data-label="Deskripsi" class="text-muted">{{ $category->description ?: '—' }}</td>
                        <td data-label="Perlengkapan"><span class="chip">{{ $category->equipment_count }} item</span></td>
                        <td data-label="Aksi" class="text-end text-nowrap">
                            <a href="{{ route('equipment-categories.edit', $category) }}" class="btn btn-sm btn-icon-sm" title="Edit" aria-label="Edit {{ $category->name }}"><i class="bi bi-pencil"></i></a>
                            <x-delete-button :url="route('equipment-categories.destroy', $category)" :name="$category->name" />
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="table-footer">
            <small class="text-muted">{{ $categories->total() }} kategori</small>
            {{ $categories->links() }}
        </div>
    @else
        <x-empty-state icon="bi-tags" title="Kategori tidak ditemukan"><a href="{{ route('equipment-categories.create') }}" class="btn btn-astra btn-sm">Tambah Kategori</a></x-empty-state>
    @endif
</div>
@endsection
