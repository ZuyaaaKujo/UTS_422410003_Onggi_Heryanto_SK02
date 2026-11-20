@extends('layouts.app')

@section('title', 'Edit Perlengkapan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('equipment.index') }}">Equipment</a></li>
    <li class="breadcrumb-item"><a href="{{ route('equipment.show', $equipment) }}">{{ $equipment->code }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<x-page-header :title="'Edit ' . $equipment->name" subtitle="Perbarui data perlengkapan." icon="bi-pencil-square" />
<form method="POST" action="{{ route('equipment.update', $equipment) }}" novalidate>
    @csrf
    @method('PUT')
    @include('equipment._form')
</form>
@endsection
