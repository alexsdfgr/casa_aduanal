@extends('layouts.app')
@section('title', 'Registrar Pedimento')
@section('content')

    <div class="dashboard-header mb-4">
        <h2><i class="bi bi-file-earmark-plus me-2"></i>Registrar Nuevo Pedimento</h2>
        <p>Completa los datos del pedimento · Anexo 22 RGCE 2024</p>
    </div>

    @include('pedimentos._form', [
        'action' => route('pedimentos.store'),
        'method' => 'POST',
        'put' => false,
    ])

@endsection