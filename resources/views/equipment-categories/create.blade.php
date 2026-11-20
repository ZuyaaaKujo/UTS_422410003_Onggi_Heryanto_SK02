@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('equipment-categories.index') }}">Categories</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<x-page-header title="Tambah Kategori Perlengkapan" subtitle="Buat kategori baru untuk inventaris." icon="bi-plus-circle" />
<form method="POST" action="{{ route('equipment-categories.store') }}" novalidate>
    @csrf
    @include('equipment-categories._form')
</form>
@endsection
