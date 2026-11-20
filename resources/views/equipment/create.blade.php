@extends('layouts.app')

@section('title', 'Tambah Perlengkapan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('equipment.index') }}">Equipment</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<x-page-header title="Tambah Perlengkapan" subtitle="Daftarkan perlengkapan baru ke inventaris." icon="bi-plus-circle" />
<form method="POST" action="{{ route('equipment.store') }}" novalidate>
    @csrf
    @include('equipment._form')
</form>
@endsection
