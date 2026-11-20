@extends('layouts.app')

@section('title', 'Edit Sesi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sessions.index') }}">Sessions</a></li>
    <li class="breadcrumb-item"><a href="{{ route('sessions.show', $session) }}">{{ $session->code }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<x-page-header :title="'Edit Sesi ' . $session->code" subtitle="Perbarui informasi sesi, detail lokasi, dan perlengkapan." icon="bi-pencil-square" />

<form method="POST" action="{{ route('sessions.update', $session) }}" novalidate>
    @csrf
    @method('PUT')
    @include('sessions._form')
</form>
@endsection
