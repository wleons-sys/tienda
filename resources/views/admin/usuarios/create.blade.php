@extends('layouts.admin')

@section('title', 'Nuevo Usuario')
@section('page-title', 'Crear Nuevo Usuario')
@section('page-subtitle', 'Registra un nuevo usuario en el sistema')

@section('content')

<div class="card">
    <form method="POST" action="{{ route('admin.usuarios.store') }}" class="form">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label>Nombre completo *</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label>Correo electrónico *</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Contraseña *</label>
                <input type="password" name="password" required>
                <small>Mínimo 6 caracteres</small>
            </div>
            <div class="form-group">
                <label>Rol *</label>
                <select name="rol" required>
                    <option value="">Seleccionar...</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->idRol }}" {{ old('rol') == $rol->idRol ? 'selected' : '' }}>
                            {{ $rol->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
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
                <i class="fas fa-save"></i> Guardar Usuario
            </button>
        </div>
    </form>
</div>

@endsection