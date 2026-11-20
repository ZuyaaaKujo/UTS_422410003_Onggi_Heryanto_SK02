@extends('layouts.app')

@section('title', 'Edit Objek')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('astronomical-objects.index') }}">Objects</a></li>
    <li class="breadcrumb-item"><a href="{{ route('astronomical-objects.show', $object) }}">{{ $object->code }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<x-page-header :title="'Edit ' . $object->name" subtitle="Perbarui data objek astronomi." icon="bi-pencil-square" />
<form method="POST" action="{{ route('astronomical-objects.update', $object) }}" novalidate>
    @csrf
    @method('PUT')
    @include('astronomical-objects._form')
</form>
@endsection
