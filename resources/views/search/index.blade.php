@extends('layouts.app')

@section('title', 'Pencarian')

@section('content')
<x-page-header title="Hasil Pencarian" :subtitle="$term !== '' ? 'Kata kunci: “' . $term . '”' : 'Masukkan kata kunci pada kolom pencarian di atas.'" icon="bi-search" />

@if ($results === null)
    <div class="glass card-pad"><x-empty-state icon="bi-search" title="Cari di seluruh data" text="Cari sesi, objek astronomi, lokasi, atau perlengkapan." /></div>
@else
    @php $total = collect($results)->sum(fn ($c) => $c->count()); @endphp
    @if ($total === 0)
        <div class="glass card-pad"><x-empty-state icon="bi-emoji-neutral" title="Tidak ada hasil" text="Coba kata kunci lain." /></div>
    @else
    <div class="row g-3">
        @if ($results['sessions']->count())
        <div class="col-lg-6"><div class="glass card-pad h-100">
            <div class="card-title-row"><h2><i class="bi bi-binoculars"></i> Sesi Pengamatan</h2><span class="chip">{{ $results['sessions']->count() }}</span></div>
            @foreach ($results['sessions'] as $s)
                <a href="{{ route('sessions.show', $s) }}" class="schedule-item"><div class="flex-grow-1"><div class="fw-semibold">{{ $s->name }}</div><small class="text-muted">{{ $s->code }} · {{ $s->astronomicalObject->name }}</small></div><x-badge :color="$s->status->color()">{{ $s->status->label() }}</x-badge></a>
            @endforeach
        </div></div>
        @endif
        @if ($results['objects']->count())
        <div class="col-lg-6"><div class="glass card-pad h-100">
            <div class="card-title-row"><h2><i class="bi bi-stars"></i> Objek Astronomi</h2><span class="chip">{{ $results['objects']->count() }}</span></div>
            @foreach ($results['objects'] as $o)
                <a href="{{ route('astronomical-objects.show', $o) }}" class="schedule-item"><div class="flex-grow-1"><div class="fw-semibold">{{ $o->name }}</div><small class="text-muted">{{ $o->code }}{{ $o->constellation ? ' · ' . $o->constellation : '' }}</small></div><x-badge :color="$o->category->color()">{{ $o->category->label() }}</x-badge></a>
            @endforeach
        </div></div>
        @endif
        @if ($results['locations']->count())
        <div class="col-lg-6"><div class="glass card-pad h-100">
            <div class="card-title-row"><h2><i class="bi bi-geo-alt"></i> Lokasi Pengamatan</h2><span class="chip">{{ $results['locations']->count() }}</span></div>
            @foreach ($results['locations'] as $l)
                <a href="{{ route('locations.show', $l) }}" class="schedule-item"><div class="flex-grow-1"><div class="fw-semibold">{{ $l->name }}</div><small class="text-muted">{{ $l->code }} · {{ collect([$l->city, $l->country])->filter()->implode(', ') }}</small></div><x-badge :color="$l->bortle_color">Bortle {{ $l->light_pollution_level }}</x-badge></a>
            @endforeach
        </div></div>
        @endif
        @if ($results['equipment']->count())
        <div class="col-lg-6"><div class="glass card-pad h-100">
            <div class="card-title-row"><h2><i class="bi bi-camera-reels"></i> Perlengkapan</h2><span class="chip">{{ $results['equipment']->count() }}</span></div>
            @foreach ($results['equipment'] as $e)
                <a href="{{ route('equipment.show', $e) }}" class="schedule-item"><div class="flex-grow-1"><div class="fw-semibold">{{ $e->name }}</div><small class="text-muted">{{ $e->code }} · {{ $e->category->name }}</small></div><x-badge :color="$e->availability_status->color()">{{ $e->availability_status->label() }}</x-badge></a>
            @endforeach
        </div></div>
        @endif
    </div>
    @endif
@endif
@endsection
