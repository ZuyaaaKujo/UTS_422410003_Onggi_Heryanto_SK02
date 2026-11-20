@extends('layouts.app')

@section('title', 'Edit Lokasi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('locations.index') }}">Locations</a></li>
    <li class="breadcrumb-item"><a href="{{ route('locations.show', $location) }}">{{ $location->code }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<x-page-header :title="'Edit ' . $location->name" subtitle="Perbarui data lokasi pengamatan." icon="bi-pencil-square" />
<form method="POST" action="{{ route('locations.update', $location) }}" novalidate>
    @csrf
    @method('PUT')
    @include('locations._form')
</form>
@endsection
