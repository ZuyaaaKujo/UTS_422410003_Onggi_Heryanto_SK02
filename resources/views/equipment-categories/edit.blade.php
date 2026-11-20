@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('equipment-categories.index') }}">Categories</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<x-page-header :title="'Edit ' . $category->name" subtitle="Perbarui kategori perlengkapan." icon="bi-pencil-square" />
<form method="POST" action="{{ route('equipment-categories.update', $category) }}" novalidate>
    @csrf
    @method('PUT')
    @include('equipment-categories._form')
</form>
@endsection
