{{-- resources/views/pedimentos/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Editar Pedimento')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('pedimentos.index') }}">Pedimentos</a></li>
    <li class="breadcrumb-item active">Editar: {{ $pedimento->num_pedimento }}</li>
@endsection
@section('content')
@include('pedimentos._form', [
    'action' => route('pedimentos.update', $pedimento),
    'method' => 'POST',
    'put'    => true,
])
@endsection
