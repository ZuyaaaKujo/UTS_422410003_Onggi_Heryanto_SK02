@extends('layouts.app')

@section('title', 'Tambah Objek')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('astronomical-objects.index') }}">Objects</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<x-page-header title="Tambah Objek Astronomi" subtitle="Daftarkan objek sasaran pengamatan baru." icon="bi-plus-circle" />
<form method="POST" action="{{ route('astronomical-objects.store') }}" novalidate>
    @csrf
    @include('astronomical-objects._form')
</form>
@endsection
