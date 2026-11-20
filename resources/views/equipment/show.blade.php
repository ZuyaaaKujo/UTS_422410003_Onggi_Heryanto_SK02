@extends('layouts.app')

@section('title', $equipment->name)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('equipment.index') }}">Equipment</a></li>
    <li class="breadcrumb-item active">{{ $equipment->code }}</li>
@endsection

@section('content')
<x-page-header :title="$equipment->name" :subtitle="'Kode perlengkapan ' . $equipment->code" icon="bi-camera-reels">
    <x-slot:actions>
        <a href="{{ route('equipment.index') }}" class="btn btn-ghost"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-astra"><i class="bi bi-pencil me-1"></i>Edit</a>
        <button type="button" class="btn btn-outline-danger" data-delete-url="{{ route('equipment.destroy', $equipment) }}" data-delete-name="{{ $equipment->name }}"><i class="bi bi-trash me-1"></i>Hapus</button>
    </x-slot:actions>
</x-page-header>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="glass card-pad">
            <div class="card-title-row"><h2><i class="bi bi-info-circle"></i> Informasi</h2></div>
            <dl class="detail-list">
                <div><dt>Kategori</dt><dd>{{ $equipment->category->name }}</dd></div>
                <div><dt>Merek</dt><dd>{{ $equipment->brand ?: '—' }}</dd></div>
                <div><dt>Model</dt><dd>{{ $equipment->model ?: '—' }}</dd></div>
                <div><dt>Jumlah (stok)</dt><dd>{{ $equipment->quantity }}</dd></div>
                <div><dt>Kondisi</dt><dd><x-badge :color="$equipment->condition->color()">{{ $equipment->condition->label() }}</x-badge></dd></div>
                <div><dt>Ketersediaan</dt><dd><x-badge :color="$equipment->availability_status->color()">{{ $equipment->availability_status->label() }}</x-badge></dd></div>
            </dl>
            <h6 class="mt-3">Deskripsi</h6>
            <p class="text-muted mb-0">{{ $equipment->description ?: 'Belum ada deskripsi.' }}</p>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="glass card-pad">
            <div class="card-title-row"><h2><i class="bi bi-clock-history"></i> Equipment Usage History</h2><small class="text-muted">{{ $equipment->sessions->count() }} sesi</small></div>
            @if ($equipment->sessions->count())
                <div class="table-responsive">
                    <table class="table table-astra table-stack align-middle mb-0">
                        <thead><tr><th>Tanggal</th><th>Sesi</th><th>Objek</th><th>Jumlah</th><th>Catatan</th><th>Status</th></tr></thead>
                        <tbody>
                        @foreach ($equipment->sessions as $s)
                            <tr>
                                <td data-label="Tanggal">{{ $s->observation_date->translatedFormat('d M Y') }}</td>
                                <td data-label="Sesi"><a class="code-link" href="{{ route('sessions.show', $s) }}">{{ $s->code }}</a><small class="d-block text-muted">{{ $s->name }}</small></td>
                                <td data-label="Objek">{{ $s->astronomicalObject->name }}</td>
                                <td data-label="Jumlah">{{ $s->pivot->quantity_used }}</td>
                                <td data-label="Catatan">{{ $s->pivot->usage_notes ?: '—' }}</td>
                                <td data-label="Status"><x-badge :color="$s->status->color()">{{ $s->status->label() }}</x-badge></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <x-empty-state icon="bi-clock-history" title="Belum pernah dipakai" text="Perlengkapan ini belum tercatat pada sesi pengamatan mana pun." />
            @endif
        </div>
    </div>
</div>
@endsection
