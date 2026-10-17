@extends('layouts.admin')

@section('title', 'Nueva Categoría')
@section('page-title', 'Crear Nueva Categoría')
@section('page-subtitle', 'Registra una nueva categoría de productos')

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb">
    <a href="{{ route('admin.categorias.index') }}" class="breadcrumb-link">
        <i class="fas fa-tags"></i>
        Categorías
    </a>
    <i class="fas fa-chevron-right breadcrumb-sep"></i>
    <span class="breadcrumb-current">Nueva categoría</span>
</div>

{{-- Card --}}
<div class="card form-card">
    <div class="card-head">
        <div class="card-head-icon" style="background: linear-gradient(135deg, #2563eb, #0ea5e9);">
            <i class="fas fa-plus"></i>
        </div>
        <div>
            <h3 class="card-head-title">Nueva Categoría</h3>
            <p class="card-head-desc">Completa los campos para crear una nueva categoría</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.categorias.store') }}" class="form">
        @csrf

        {{-- Nombre --}}
        <div class="form-group">
            <label class="form-label" for="nombre">
                Nombre de la categoría
                <span class="form-required">*</span>
            </label>
            <div class="form-input-wrap">
                <i class="fas fa-tag form-input-icon"></i>
                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    class="form-input"
                    placeholder="Ej: Consumo Diario, Librería, Farmacia"
                    value="{{ old('nombre') }}"
                    maxlength="50"
                    required
                    autofocus
                >
            </div>
            <div class="form-hint">
                <i class="fas fa-info-circle"></i>
                El nombre debe ser único y no puede superar los 50 caracteres
            </div>
        </div>

        {{-- Descripción --}}
        <div class="form-group">
            <label class="form-label" for="descripcion">
                Descripción
                <span class="form-optional">(opcional)</span>
            </label>
            <textarea
                id="descripcion"
                name="descripcion"
                class="form-textarea"
                placeholder="Describe brevemente qué tipo de productos incluye esta categoría..."
                maxlength="255"
                rows="4"
            >{{ old('descripcion') }}</textarea>
            <div class="form-hint">
                <i class="fas fa-info-circle"></i>
                Máximo 255 caracteres
            </div>
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
            <a href="{{ route('admin.categorias.index') }}" class="btn btn-ghost">
                <i class="fas fa-times"></i>
                <span>Cancelar</span>
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                <span>Guardar Categoría</span>
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
        transition: color 0.2s ease;
    }

    .breadcrumb-link:hover {
        color: #1e40af;
    }

    .breadcrumb-link i {
        font-size: 12px;
    }

    .breadcrumb-sep {
        color: #cbd5e1;
        font-size: 10px;
    }

    .breadcrumb-current {
        color: #64748b;
        font-weight: 500;
    }

    /* Card */
    .card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        max-width: 720px;
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
        box-shadow: 0 8px 20px -6px rgba(37, 99, 235, 0.4);
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

    /* Form */
    .form {
        padding: 28px;
    }

    .form-group {
        margin-bottom: 24px;
    }

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

    .form-required {
        color: #ef4444;
        font-size: 14px;
    }

    .form-optional {
        color: #94a3b8;
        font-weight: 500;
        font-size: 11px;
    }

    .form-input-wrap {
        position: relative;
    }

    .form-input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
        transition: color 0.25s ease;
    }

    .form-input {
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

    .form-input::placeholder {
        color: #94a3b8;
    }

    .form-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    .form-input:focus ~ .form-input-icon {
        color: #2563eb;
    }

    .form-textarea {
        width: 100%;
        padding: 14px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 11px;
        font-size: 14px;
        font-family: 'Inter', sans-serif;
        color: #0f172a;
        background: #ffffff;
        outline: none;
        transition: all 0.25s ease;
        resize: vertical;
        min-height: 110px;
        line-height: 1.6;
    }

    .form-textarea::placeholder {
        color: #94a3b8;
    }

    .form-textarea:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
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

    .form-hint i {
        font-size: 11px;
        color: #cbd5e1;
    }

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

    .form-errors-head i {
        color: #ef4444;
    }

    .form-errors-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .form-errors-list li {
        color: #991b1b;
        font-size: 12px;
        padding: 3px 0;
        padding-left: 18px;
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

    .btn-ghost {
        background: #f1f5f9;
        color: #475569;
    }

    .btn-ghost:hover {
        background: #e2e8f0;
    }

    @media (max-width: 640px) {
        .form { padding: 20px; }
        .card-head { padding: 18px; }
        .form-actions {
            flex-direction: column-reverse;
        }
        .form-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

@endsection