@extends('layouts.admin')

@section('title', 'Usuarios')
@section('page-title', 'Gestión de Usuarios')
@section('page-subtitle', 'Administra los usuarios del sistema')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
    </div>
@endif

<div class="card">
    <div class="card-head">
        <form method="GET" class="filters">
            <input type="text" name="buscar" placeholder="Buscar por nombre o correo..." value="{{ request('buscar') }}">
            <select name="rol">
                <option value="">Todos los roles</option>
                @foreach($roles as $rol)
                    <option value="{{ $rol->idRol }}" {{ request('rol') == $rol->idRol ? 'selected' : '' }}>
                        {{ $rol->nombre }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Filtrar
            </button>
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-ghost">Limpiar</a>
        </form>

        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($usuarios as $u)
                @php
                    $rol = DB::table('roles_usuarios')
                        ->join('roles', 'roles_usuarios.idRol', '=', 'roles.idRol')
                        ->where('roles_usuarios.idUsuario', $u->id)
                        ->select('roles.nombre')
                        ->first();
                @endphp
                <tr>
                    <td>#{{ str_pad($u->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td><strong>{{ $u->name }}</strong></td>
                    <td>{{ $u->email }}</td>
                    <td>
                        <span class="badge {{ $rol && $rol->nombre === 'Administrador' ? 'badge-blue' : 'badge-green' }}">
                            {{ $rol->nombre ?? 'Sin rol' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $u->activo ? 'badge-green' : 'badge-red' }}">
                            {{ $u->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="actions">
                        <a href="{{ route('admin.usuarios.show', $u->id) }}" class="btn-icon" title="Ver">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.usuarios.edit', $u->id) }}" class="btn-icon" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.usuarios.destroy', $u->id) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar usuario?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon btn-icon-danger" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty">No hay usuarios registrados</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $usuarios->links() }}
    </div>
</div>

@endsection