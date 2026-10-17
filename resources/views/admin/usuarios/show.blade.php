@extends('layouts.admin')

@section('title', 'Ver Usuario')
@section('page-title', 'Detalle del Usuario')
@section('page-subtitle', $usuario->name)

@section('content')

<div class="card">
    <div class="detail-grid">
        <div class="detail-item">
            <span class="detail-label">ID</span>
            <span class="detail-value">#{{ str_pad($usuario->id, 4, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Nombre</span>
            <span class="detail-value">{{ $usuario->name }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Correo</span>
            <span class="detail-value">{{ $usuario->email }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Rol</span>
            <span class="detail-value">{{ $rol->nombre ?? 'Sin rol' }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Estado</span>
            <span class="badge {{ $usuario->activo ? 'badge-green' : 'badge-red' }}">
                {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
            </span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Creado</span>
            <span class="detail-value">{{ $usuario->created_at }}</span>
        </div>
    </div>

    <div class="form-actions">
        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-ghost">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Editar
        </a>
    </div>
</div>

@endsection