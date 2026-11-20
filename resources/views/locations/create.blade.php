@extends('layouts.app')

@section('title', 'Tambah Lokasi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('locations.index') }}">Locations</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<x-page-header title="Tambah Lokasi Pengamatan" subtitle="Daftarkan lokasi observasi baru." icon="bi-plus-circle" />
<form method="POST" action="{{ route('locations.store') }}" novalidate>
    @csrf
    @include('locations._form')
</form>
@endsection
