@extends('layouts.admin')

@section('title', 'Editar Producto')
@section('page-title', 'Editar Producto')
@section('page-subtitle', $producto->nombre)

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb">
    <a href="{{ route('admin.productos.index') }}" class="breadcrumb-link">
        <i class="fas fa-boxes"></i>
        Productos
    </a>
    <i class="fas fa-chevron-right breadcrumb-sep"></i>
    <span class="breadcrumb-current">Editar: {{ $producto->nombre }}</span>
</div>

{{-- Card --}}
<div class="card form-card">
    <div class="card-head">
        <div class="card-head-icon" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
            <i class="fas fa-edit"></i>
        </div>
        <div>
            <h3 class="card-head-title">Editar Producto</h3>
            <p class="card-head-desc">
                Modificando: <strong>{{ $producto->nombre }}</strong>
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.productos.update', $producto->idProducto) }}" class="form">
        @csrf
        @method('PUT')

        {{-- Fila 1: Nombre + Categoría --}}
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="nombre">
                    Nombre del producto
                    <span class="form-required">*</span>
                </label>
                <div class="form-input-wrap">
                    <i class="fas fa-box form-input-icon"></i>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        class="form-input"
                        value="{{ old('nombre', $producto->nombre) }}"
                        maxlength="150"
                        required
                    >
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="idCategoria">
                    Categoría
                    <span class="form-required">*</span>
                </label>
                <div class="form-select-wrap">
                    <i class="fas fa-tags form-input-icon"></i>
                    <select id="idCategoria" name="idCategoria" class="form-select" required>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->idCategoria }}"
                                {{ $producto->idCategoria == $cat->idCategoria ? 'selected' : '' }}>
                                {{ $cat->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Fila 2: Precio + Código --}}
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="precio">
                    Precio de venta
                    <span class="form-required">*</span>
                </label>
                <div class="form-input-wrap">
                    <span class="form-input-prefix">Q</span>
                    <input
                        type="number"
                        id="precio"
                        name="precio"
                        class="form-input form-input-prefixed"
                        value="{{ old('precio', $producto->precio) }}"
                        step="0.01"
                        min="0"
                        required
                    >
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="codigo_interno">
                    Código interno
                    <span class="form-optional">(opcional)</span>
                </label>
                <div class="form-input-wrap">
                    <i class="fas fa-barcode form-input-icon"></i>
                    <input
                        type="text"
                        id="codigo_interno"
                        name="codigo_interno"
                        class="form-input"
                        value="{{ old('codigo_interno', $producto->codigo_interno) }}"
                        maxlength="50"
                    >
                </div>
            </div>
        </div>

        {{-- Fila 3: Stock --}}
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="stock_actual">
                    Stock actual
                    <span class="form-required">*</span>
                </label>
                <div class="form-input-wrap">
                    <i class="fas fa-cubes form-input-icon"></i>
                    <input
                        type="number"
                        id="stock_actual"
                        name="stock_actual"
                        class="form-input"
                        value="{{ old('stock_actual', $producto->stock_actual) }}"
                        min="0"
                        required
                    >
                </div>
                <div class="form-hint">
                    <i class="fas fa-info-circle"></i>
                    Stock mínimo actual: {{ $producto->stock_minimo }}
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="stock_minimo">
                    Stock mínimo
                    <span class="form-required">*</span>
                </label>
                <div class="form-input-wrap">
                    <i class="fas fa-exclamation-triangle form-input-icon"></i>
                    <input
                        type="number"
                        id="stock_minimo"
                        name="stock_minimo"
                        class="form-input"
                        value="{{ old('stock_minimo', $producto->stock_minimo) }}"
                        min="0"
                        required
                    >
                </div>
            </div>
        </div>

        {{-- Estado --}}
        <div class="form-group">
            <label class="form-label">Estado del producto</label>
            <label class="form-switch">
                <input type="checkbox" name="activo" {{ $producto->activo ? 'checked' : '' }}>
                <span class="form-switch-track">
                    <span class="form-switch-thumb"></span>
                </span>
                <div class="form-switch-info">
                    <span class="form-switch-title">Producto activo</span>
                    <span class="form-switch-desc">
                        Los productos inactivos no aparecerán en el catálogo
                    </span>
                </div>
            </label>
        </div>

        {{-- Errores --}}
        @if($errors->any())
            <div class="form-errors">
                <div class="form-errors-head">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Por favor corrige los siguientes errores:</span>
                </div>
                <ul class="form-errors-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Acciones --}}
        <div class="form-actions">
            <a href="{{ route('admin.productos.index') }}" class="btn btn-ghost">
                <i class="fas fa-times"></i>
                <span>Cancelar</span>
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                <span>Actualizar Producto</span>
            </button>
        </div>

    </form>
</div>

<style>
    /* Breadcrumb */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        font-size: 13px;
    }
    .breadcrumb-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
    }
    .breadcrumb-link:hover { color: #1e40af; }
    .breadcrumb-link i { font-size: 12px; }
    .breadcrumb-sep { color: #cbd5e1; font-size: 10px; }
    .breadcrumb-current { color: #64748b; font-weight: 500; }

    /* Card */
    .card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        max-width: 820px;
    }

    .card-head {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 24px;
        border-bottom: 1px solid #f1f5f9;
        background: #f8fafc;
    }

    .card-head-icon {
        width: 48px;
        height: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 20px;
        flex-shrink: 0;
        box-shadow: 0 8px 20px -6px rgba(217, 119, 6, 0.4);
    }

    .card-head-title {
        font-family: 'Poppins', sans-serif;
        font-size: 17px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 3px;
    }

    .card-head-desc {
        font-size: 13px;
        color: #64748b;
    }

    .card-head-desc strong { color: #0f172a; font-weight: 700; }

    /* Form */
    .form { padding: 28px; }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 22px;
    }

    .form-group { display: flex; flex-direction: column; margin-bottom: 20px; }

    .form-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: #1e3a8a;
        margin-bottom: 10px;
    }

    .form-required { color: #ef4444; font-size: 14px; }
    .form-optional { color: #94a3b8; font-weight: 500; font-size: 11px; }

    .form-input-wrap, .form-select-wrap { position: relative; }

    .form-input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
        z-index: 2;
    }

    .form-input-prefix {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 14px;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
        pointer-events: none;
        z-index: 2;
    }

    .form-input, .form-select {
        width: 100%;
        padding: 14px 16px 14px 44px;
        border: 1.5px solid #e2e8f0;
        border-radius: 11px;
        font-size: 14px;
        font-family: 'Inter', sans-serif;
        color: #0f172a;
        background: #ffffff;
        outline: none;
        transition: all 0.25s ease;
    }

    .form-input-prefixed { padding-left: 38px; }

    .form-input:focus, .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3e%3cpath fill='%2394a3b8' d='M6 8L0 0h12z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 16px center;
        padding-right: 40px;
        cursor: pointer;
    }

    .form-hint {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #94a3b8;
        margin-top: 8px;
        font-weight: 500;
    }
    .form-hint i { font-size: 11px; color: #cbd5e1; }

    /* Switch */
    .form-switch {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .form-switch:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }

    .form-switch input { display: none; }

    .form-switch-track {
        width: 48px;
        height: 26px;
        background: #cbd5e1;
        border-radius: 100px;
        position: relative;
        transition: background 0.3s ease;
        flex-shrink: 0;
    }

    .form-switch-thumb {
        position: absolute;
        top: 3px;
        left: 3px;
        width: 20px;
        height: 20px;
        background: #ffffff;
        border-radius: 50%;
        transition: transform 0.3s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    .form-switch input:checked + .form-switch-track {
        background: linear-gradient(135deg, #2563eb, #0ea5e9);
    }

    .form-switch input:checked + .form-switch-track .form-switch-thumb {
        transform: translateX(22px);
    }

    .form-switch-info { display: flex; flex-direction: column; gap: 3px; }
    .form-switch-title { font-size: 13px; font-weight: 600; color: #0f172a; }
    .form-switch-desc { font-size: 12px; color: #64748b; line-height: 1.4; }

    /* Errors */
    .form-errors {
        background: #fef2f2;
        border-left: 4px solid #ef4444;
        border-radius: 11px;
        padding: 16px 18px;
        margin-bottom: 24px;
    }

    .form-errors-head {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #991b1b;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 10px;
    }
    .form-errors-head i { color: #ef4444; }

    .form-errors-list { list-style: none; padding: 0; margin: 0; }
    .form-errors-list li {
        color: #991b1b;
        font-size: 12px;
        padding: 3px 0 3px 18px;
        position: relative;
    }
    .form-errors-list li::before {
        content: '•';
        position: absolute;
        left: 6px;
        color: #ef4444;
        font-weight: 700;
    }

    /* Actions */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 22px;
        border-top: 1px solid #f1f5f9;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 22px;
        border-radius: 11px;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2563eb, #0ea5e9);
        color: #ffffff;
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.45);
    }

    .btn-ghost { background: #f1f5f9; color: #475569; }
    .btn-ghost:hover { background: #e2e8f0; }

    @media (max-width: 640px) {
        .form { padding: 20px; }
        .card-head { padding: 18px; }
        .form-row { grid-template-columns: 1fr; gap: 0; }
        .form-row .form-group { margin-bottom: 22px; }
        .form-actions { flex-direction: column-reverse; }
        .form-actions .btn { width: 100%; justify-content: center; }
    }
</style>

@endsection