@extends('layouts.admin')

@section('title', 'Editar Usuario')
@section('page-title', 'Editar Usuario')
@section('page-subtitle', $usuario->name)

@section('content')

<div class="card">
    <form method="POST" action="{{ route('admin.usuarios.update', $usuario->id) }}" class="form">
        @csrf @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label>Nombre completo *</label>
                <input type="text" name="name" value="{{ old('name', $usuario->name) }}" required>
            </div>
            <div class="form-group">
                <label>Correo electrónico *</label>
                <input type="email" name="email" value="{{ old('email', $usuario->email) }}" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Nueva contraseña (opcional)</label>
                <input type="password" name="password">
                <small>Dejar vacío para mantener la actual</small>
            </div>
            <div class="form-group">
                <label>Rol *</label>
                <select name="rol" required>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->idRol }}" {{ $rolActual && $rolActual->idRol == $rol->idRol ? 'selected' : '' }}>
                            {{ $rol->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="checkbox">
                <input type="checkbox" name="activo" {{ $usuario->activo ? 'checked' : '' }}>
                Usuario activo
            </label>
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-actions">
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-ghost">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Actualizar Usuario
            </button>
        </div>
    </form>
</div>

@endsection