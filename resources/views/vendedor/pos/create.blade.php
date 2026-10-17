@extends('layouts.admin')

@section('title', 'Nuevo Movimiento')
@section('page-title', 'Registrar Movimiento')
@section('page-subtitle', 'Registra una entrada, salida o ajuste de inventario')

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb">
    <a href="{{ route('admin.movimientos.index') }}" class="breadcrumb-link">
        <i class="fas fa-exchange-alt"></i> Movimientos
    </a>
    <i class="fas fa-chevron-right breadcrumb-sep"></i>
    <span class="breadcrumb-current">Nuevo movimiento</span>
</div>

<div class="card form-card">
    <div class="card-head">
        <div class="card-head-icon" style="background: linear-gradient(135deg, #2563eb, #0ea5e9);">
            <i class="fas fa-plus"></i>
        </div>
        <div>
            <h3 class="card-head-title">Nuevo Movimiento</h3>
            <p class="card-head-desc">Completa los datos para registrar el movimiento</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.movimientos.store') }}" class="form">
        @csrf

        {{-- Producto --}}
        <div class="form-group">
            <label class="form-label">Producto <span class="form-required">*</span></label>
            <div class="form-select-wrap">
                <i class="fas fa-box form-input-icon"></i>
                <select name="idProducto" id="idProducto" class="form-select" required onchange="actualizarInfo()">
                    <option value="">Seleccionar producto...</option>
                    @foreach($productos as $prod)
                        <option value="{{ $prod->idProducto }}"
                            data-stock="{{ $prod->stock_actual }}"
                            data-minimo="{{ $prod->stock_minimo }}"
                            data-nombre="{{ $prod->nombre }}"
                            {{ old('idProducto') == $prod->idProducto ? 'selected' : '' }}>
                            {{ $prod->nombre }} — Stock: {{ $prod->stock_actual }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div id="infoProducto" class="info-producto" style="display: none;">
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <span>Stock actual: <strong id="stockActual">0</strong> unidades</span>
                </div>
            </div>
        </div>

        {{-- Tipo de movimiento --}}
        <div class="form-group">
            <label class="form-label">Tipo de movimiento <span class="form-required">*</span></label>
            <div class="tipo-grid">
                <label class="tipo-option tipo-option-entrada">
                    <input type="radio" name="tipo" value="Entrada" {{ old('tipo') == 'Entrada' ? 'checked' : '' }} required>
                    <div class="tipo-content">
                        <div class="tipo-icon"><i class="fas fa-arrow-down"></i></div>
                        <div class="tipo-info">
                            <div class="tipo-name">Entrada</div>
                            <div class="tipo-desc">Incrementa el stock</div>
                        </div>
                    </div>
                </label>

                <label class="tipo-option tipo-option-salida">
                    <input type="radio" name="tipo" value="Salida" {{ old('tipo') == 'Salida' ? 'checked' : '' }}>
                    <div class="tipo-content">
                        <div class="tipo-icon"><i class="fas fa-arrow-up"></i></div>
                        <div class="tipo-info">
                            <div class="tipo-name">Salida</div>
                            <div class="tipo-desc">Disminuye el stock</div>
                        </div>
                    </div>
                </label>

                <label class="tipo-option tipo-option-ajuste">
                    <input type="radio" name="tipo" value="Ajuste" {{ old('tipo') == 'Ajuste' ? 'checked' : '' }}>
                    <div class="tipo-content">
                        <div class="tipo-icon"><i class="fas fa-sliders-h"></i></div>
                        <div class="tipo-info">
                            <div class="tipo-name">Ajuste</div>
                            <div class="tipo-desc">Corrige el stock</div>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        {{-- Cantidad + Referencia --}}
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" id="labelCantidad">Cantidad <span class="form-required">*</span></label>
                <div class="form-input-wrap">
                    <i class="fas fa-cubes form-input-icon"></i>
                    <input type="number" name="cantidad" id="cantidad" class="form-input" value="{{ old('cantidad', 1) }}" min="1" required>
                </div>
                <div class="form-hint" id="hintCantidad">
                    <i class="fas fa-info-circle"></i>
                    <span id="hintText">Cantidad del movimiento</span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Referencia <span class="form-optional">(opcional)</span></label>
                <div class="form-input-wrap">
                    <i class="fas fa-tag form-input-icon"></i>
                    <input type="text" name="referencia" class="form-input" value="{{ old('referencia') }}" maxlength="255" placeholder="Ej: Compra factura #123">
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="form-errors">
                <div class="form-errors-head"><i class="fas fa-exclamation-triangle"></i> <span>Corrige los errores:</span></div>
                <ul class="form-errors-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-actions">
            <a href="{{ route('admin.movimientos.index') }}" class="btn btn-ghost">
                <i class="fas fa-times"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Registrar Movimiento
            </button>
        </div>

    </form>
</div>

<script>
    function actualizarInfo() {
        const select = document.getElementById('idProducto');
        const option = select.options[select.selectedIndex];
        const stock = option.dataset.stock;
        const info = document.getElementById('infoProducto');

        if (stock !== undefined) {
            document.getElementById('stockActual').textContent = stock;
            info.style.display = 'block';
        } else {
            info.style.display = 'none';
        }
    }

    // Actualizar label según tipo
    document.querySelectorAll('input[name="tipo"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const label = document.getElementById('labelCantidad');
            const hint = document.getElementById('hintText');

            if (this.value === 'Ajuste') {
                label.innerHTML = 'Nuevo stock <span class="form-required">*</span>';
                hint.textContent = 'Ingresa el stock final que debe quedar';
            } else {
                label.innerHTML = 'Cantidad <span class="form-required">*</span>';
                hint.textContent = 'Cantidad del movimiento';
            }
        });
    });

    // Ejecutar al cargar
    document.addEventListener('DOMContentLoaded', function () {
        actualizarInfo();
        const checked = document.querySelector('input[name="tipo"]:checked');
        if (checked) checked.dispatchEvent(new Event('change'));
    });
</script>

<style>
    .breadcrumb { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; font-size: 13px; }
    .breadcrumb-link { display: inline-flex; align-items: center; gap: 7px; color: #2563eb; text-decoration: none; font-weight: 600; }
    .breadcrumb-link:hover { color: #1e40af; }
    .breadcrumb-link i { font-size: 12px; }
    .breadcrumb-sep { color: #cbd5e1; font-size: 10px; }
    .breadcrumb-current { color: #64748b; font-weight: 500; }

    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; max-width: 820px; }
    .card-head { display: flex; align-items: center; gap: 16px; padding: 24px; border-bottom: 1px solid #f1f5f9; background: #f8fafc; }
    .card-head-icon { width: 48px; height: 48px; border-radius: 13px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; flex-shrink: 0; box-shadow: 0 8px 20px -6px rgba(37,99,235,0.4); }
    .card-head-title { font-family: 'Poppins', sans-serif; font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 3px; }
    .card-head-desc { font-size: 13px; color: #64748b; }

    .form { padding: 28px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 22px; }
    .form-group { display: flex; flex-direction: column; margin-bottom: 22px; }
    .form-label { display: flex; align-items: center; gap: 6px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600; color: #1e3a8a; margin-bottom: 10px; }
    .form-required { color: #ef4444; font-size: 14px; }
    .form-optional { color: #94a3b8; font-weight: 500; font-size: 11px; }

    .form-input-wrap, .form-select-wrap { position: relative; }
    .form-input-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; pointer-events: none; z-index: 2; }
    .form-input, .form-select { width: 100%; padding: 14px 16px 14px 44px; border: 1.5px solid #e2e8f0; border-radius: 11px; font-size: 14px; font-family: 'Inter', sans-serif; color: #0f172a; background: #fff; outline: none; transition: all 0.25s ease; }
    .form-input:focus, .form-select:focus { border-color: #2563eb; box-shadow: 0 0 0 4px rgba(37,99,235,0.1); }
    .form-select { appearance: none; background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3e%3cpath fill='%2394a3b8' d='M6 8L0 0h12z'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 16px center; padding-right: 40px; cursor: pointer; }

    .form-hint { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #94a3b8; margin-top: 8px; font-weight: 500; }
    .form-hint i { font-size: 11px; color: #cbd5e1; }

    .info-producto { margin-top: 10px; }
    .info-box { display: flex; align-items: center; gap: 8px; padding: 10px 14px; background: #eff6ff; border-radius: 10px; font-size: 12px; color: #1d4ed8; font-weight: 600; }
    .info-box i { color: #3b82f6; }

    /* Tipos */
    .tipo-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
    .tipo-option { display: block; cursor: pointer; }
    .tipo-option input { display: none; }
    .tipo-content { display: flex; align-items: center; gap: 12px; padding: 16px; background: #fff; border: 2px solid #e2e8f0; border-radius: 12px; transition: all 0.25s ease; }
    .tipo-icon { width: 40px; height: 40px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
    .tipo-info { display: flex; flex-direction: column; gap: 2px; }
    .tipo-name { font-size: 13px; font-weight: 700; color: #0f172a; }
    .tipo-desc { font-size: 11px; color: #94a3b8; }

    .tipo-option-entrada .tipo-icon { background: #ecfdf5; color: #059669; }
    .tipo-option-salida .tipo-icon { background: #fef3c7; color: #d97706; }
    .tipo-option-ajuste .tipo-icon { background: #f5f3ff; color: #7c3aed; }

    .tipo-option input:checked + .tipo-content { border-color: #2563eb; background: #f0f9ff; box-shadow: 0 0 0 4px rgba(37,99,235,0.1); }
    .tipo-option-entrada input:checked + .tipo-content { border-color: #10b981; background: #ecfdf5; box-shadow: 0 0 0 4px rgba(16,185,129,0.1); }
    .tipo-option-salida input:checked + .tipo-content { border-color: #f59e0b; background: #fffbeb; box-shadow: 0 0 0 4px rgba(245,158,11,0.1); }
    .tipo-option-ajuste input:checked + .tipo-content { border-color: #8b5cf6; background: #f5f3ff; box-shadow: 0 0 0 4px rgba(139,92,246,0.1); }

    .form-errors { background: #fef2f2; border-left: 4px solid #ef4444; border-radius: 11px; padding: 16px 18px; margin-bottom: 24px; }
    .form-errors-head { display: flex; align-items: center; gap: 8px; color: #991b1b; font-size: 13px; font-weight: 700; margin-bottom: 10px; }
    .form-errors-head i { color: #ef4444; }
    .form-errors-list { list-style: none; padding: 0; margin: 0; }
    .form-errors-list li { color: #991b1b; font-size: 12px; padding: 3px 0 3px 18px; position: relative; }
    .form-errors-list li::before { content: '•'; position: absolute; left: 6px; color: #ef4444; font-weight: 700; }

    .form-actions { display: flex; justify-content: flex-end; gap: 12px; padding-top: 22px; border-top: 1px solid #f1f5f9; }

    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 22px; border-radius: 11px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all 0.25s ease; }
    .btn-primary { background: linear-gradient(135deg, #2563eb, #0ea5e9); color: #fff; box-shadow: 0 6px 16px rgba(37,99,235,0.3); }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(37,99,235,0.45); }
    .btn-ghost { background: #f1f5f9; color: #475569; }
    .btn-ghost:hover { background: #e2e8f0; }

    @media (max-width: 640px) {
        .form { padding: 20px; }
        .card-head { padding: 18px; }
        .form-row { grid-template-columns: 1fr; gap: 0; }
        .tipo-grid { grid-template-columns: 1fr; }
        .form-actions { flex-direction: column-reverse; }
        .form-actions .btn { width: 100%; justify-content: center; }
    }
</style>

@endsection