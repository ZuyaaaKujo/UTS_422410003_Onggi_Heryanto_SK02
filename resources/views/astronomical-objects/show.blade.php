@extends('layouts.app')

@section('title', $object->name)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('astronomical-objects.index') }}">Objects</a></li>
    <li class="breadcrumb-item active">{{ $object->code }}</li>
@endsection

@section('content')
<x-page-header :title="$object->name" :subtitle="'Kode objek ' . $object->code" icon="bi-stars">
    <x-slot:actions>
        <a href="{{ route('astronomical-objects.index') }}" class="btn btn-ghost"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        <a href="{{ route('astronomical-objects.edit', $object) }}" class="btn btn-astra"><i class="bi bi-pencil me-1"></i>Edit</a>
        <button type="button" class="btn btn-outline-danger" data-delete-url="{{ route('astronomical-objects.destroy', $object) }}" data-delete-name="{{ $object->name }}"><i class="bi bi-trash me-1"></i>Hapus</button>
    </x-slot:actions>
</x-page-header>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="glass overflow-hidden">
            <div class="object-hero"><img src="{{ $object->image_src }}" alt="{{ $object->name }}" data-fallback="{{ $object->category->fallbackImage() }}"></div>
            <div class="card-pad">
                <dl class="detail-list mb-0">
                    <div><dt>Kategori</dt><dd><x-badge :color="$object->category->color()">{{ $object->category->label() }}</x-badge></dd></div>
                    <div><dt>Konstelasi</dt><dd>{{ $object->constellation ?: '—' }}</dd></div>
                    <div><dt>Total sesi</dt><dd>{{ $object->sessions()->count() }}</dd></div>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="glass card-pad mb-3">
            <div class="card-title-row"><h2><i class="bi bi-journal-text"></i> Deskripsi</h2></div>
            <p>{{ $object->description ?: 'Belum ada deskripsi.' }}</p>
            <h6>Informasi Astronomi</h6>
            <p class="text-muted mb-0">{{ $object->astronomical_info ?: 'Belum ada informasi.' }}</p>
        </div>
        <div class="glass card-pad">
            <div class="card-title-row"><h2><i class="bi bi-binoculars"></i> Sesi Pengamatan Terkait</h2></div>
            @forelse ($object->sessions as $session)
                <a href="{{ route('sessions.show', $session) }}" class="schedule-item">
                    <div class="schedule-date"><strong>{{ $session->observation_date->format('d') }}</strong><small>{{ $session->observation_date->translatedFormat('M') }}</small></div>
                    <div class="flex-grow-1"><div class="fw-semibold">{{ $session->name }}</div><small class="text-muted">{{ $session->code }}</small></div>
                    <x-badge :color="$session->status->color()">{{ $session->status->label() }}</x-badge>
                </a>
            @empty
                <x-empty-state icon="bi-binoculars" title="Belum ada sesi untuk objek ini" />
            @endforelse
        </div>
    </div>
</div>
@endsection
