@extends('layouts.vendedor')

@section('title', 'Consultar Productos')
@section('page-title', 'Consultar Productos')
@section('page-subtitle', 'Ver stock y precios disponibles')

@section('content')

<div class="filters">
    <form method="GET" class="filters-form">
        <div class="filter-input">
            <i class="fas fa-search"></i>
            <input type="text" name="buscar" placeholder="Buscar producto..." value="{{ request('buscar') }}">
        </div>
        <select name="categoria">
            <option value="">Todas las categorías</option>
            @foreach($categorias as $cat)
                <option value="{{ $cat->idCategoria }}" {{ request('categoria') == $cat->idCategoria ? 'selected' : '' }}>
                    {{ $cat->nombre }}
                </option>
            @endforeach
        </select>
        <button class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
        @if(request()->hasAny(['buscar','categoria']))
            <a href="{{ route('vendedor.productos.index') }}" class="btn btn-ghost">Limpiar</a>
        @endif
    </form>
</div>

<div class="products-grid">
    @forelse($productos as $p)
        <div class="product-card">
            <div class="product-icon"><i class="fas fa-box"></i></div>
            <div class="product-name">{{ $p->nombre }}</div>
            <div class="product-cat">{{ $p->categoria_nombre }}</div>
            <div class="product-row">
                <span class="product-price">Q{{ number_format($p->precio, 2) }}</span>
                <span class="product-stock {{ $p->stock_actual < $p->stock_minimo ? 'low' : '' }}">
                    Stock: {{ $p->stock_actual }}
                </span>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <p>No hay productos disponibles</p>
        </div>
    @endforelse
</div>

<div class="pagination-wrap">
    {{ $productos->appends(request()->query())->links() }}
</div>

<style>
    .filters { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px; margin-bottom: 20px; }
    .filters-form { display: flex; gap: 10px; flex-wrap: wrap; }
    .filter-input { position: relative; flex: 1; min-width: 240px; }
    .filter-input i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px; }
    .filter-input input, .filters-form select { width: 100%; padding: 11px 14px 11px 40px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 13px; outline: none; background: #fff; }
    .filters-form select { padding-left: 14px; min-width: 200px; cursor: pointer; }

    .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
    .product-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; transition: all 0.25s ease; }
    .product-card:hover { transform: translateY(-3px); box-shadow: 0 15px 30px -12px rgba(15,23,42,0.1); }
    .product-icon { width: 46px; height: 46px; background: #ecfdf5; color: #059669; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; margin-bottom: 14px; }
    .product-name { font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 4px; line-height: 1.3; }
    .product-cat { font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 14px; }
    .product-row { display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid #f1f5f9; }
    .product-price { font-family: 'Poppins', sans-serif; font-size: 16px; font-weight: 800; color: #059669; }
    .product-stock { font-size: 11px; font-weight: 700; color: #64748b; background: #f1f5f9; padding: 4px 10px; border-radius: 100px; }
    .product-stock.low { background: #fef2f2; color: #dc2626; }

    .empty-state { grid-column: 1/-1; padding: 60px 20px; text-align: center; color: #94a3b8; }
    .empty-state i { font-size: 48px; color: #cbd5e1; margin-bottom: 12px; display: block; }

    .pagination-wrap { margin-top: 20px; display: flex; justify-content: center; }
</style>

@endsection