@extends('layouts.app')

@section('title', 'Tambah Sesi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sessions.index') }}">Sessions</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<x-page-header title="Tambah Sesi Pengamatan" subtitle="Isi informasi sesi, detail lokasi, dan pilih perlengkapan yang akan digunakan." icon="bi-plus-circle" />

<form method="POST" action="{{ route('sessions.store') }}" novalidate>
    @csrf
    @include('sessions._form')
</form>
@endsection
