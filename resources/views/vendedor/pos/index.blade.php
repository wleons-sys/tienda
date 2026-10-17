@extends('layouts.vendedor')

@section('title', 'Punto de Venta')
@section('page-title', 'Punto de Venta')
@section('page-subtitle', 'Registra una nueva venta rápidamente')

@section('content')

<div class="pos-layout">

    {{-- ============================================
         IZQUIERDA: Buscador + Grid productos
    ============================================ --}}
    <div class="pos-products">

        {{-- Buscador --}}
        <div class="pos-search">
            <div class="search-input-wrap">
                <i class="fas fa-search"></i>
                <input type="text"
                       id="buscarInput"
                       placeholder="Buscar producto por nombre o código..."
                       autocomplete="off"
                       autofocus>
                <span class="search-hint">Presiona <kbd>F2</kbd> para enfocar</span>
            </div>
            <div class="search-results" id="searchResults"></div>
        </div>

        {{-- Grid productos --}}
        <div class="products-grid">
            @forelse($productos as $producto)
                <div class="product-card"
                     onclick="agregarProducto(
                         {{ $producto->idProducto }},
                         '{{ addslashes($producto->nombre) }}',
                         {{ $producto->precio }},
                         {{ $producto->stock_actual }}
                     )">
                    <div class="product-card-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="product-card-body">
                        <div class="product-card-name">{{ $producto->nombre }}</div>
                        <div class="product-card-cat">{{ $producto->categoria }}</div>
                        <div class="product-card-footer">
                            <span class="product-card-price">
                                Q{{ number_format($producto->precio, 2) }}
                            </span>
                            <span class="product-card-stock">
                                Stock: {{ $producto->stock_actual }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-products">
                    <i class="fas fa-box-open"></i>
                    <h3>No hay productos disponibles</h3>
                    <p>Agrega productos desde el panel de administrador</p>
                </div>
            @endforelse
        </div>

    </div>

    {{-- ============================================
         DERECHA: Carrito de venta
    ============================================ --}}
    <div class="pos-cart">

        {{-- Header carrito --}}
        <div class="cart-header">
            <div class="cart-header-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="cart-header-info">
                <div class="cart-header-title">Venta actual</div>
                <div class="cart-header-sub" id="cartCount">0 productos</div>
            </div>
        </div>

        {{-- Lista de items --}}
        <div class="cart-body" id="cartBody">
            <div class="cart-empty" id="cartEmpty">
                <i class="fas fa-shopping-basket"></i>
                <h4>Carrito vacío</h4>
                <p>Agrega productos para comenzar la venta</p>
            </div>
        </div>

        {{-- Footer carrito --}}
        <div class="cart-footer">

            <div class="cart-summary">
                <div class="cart-summary-row">
                    <span>Subtotal</span>
                    <span id="cartSubtotal">Q0.00</span>
                </div>

                <div class="cart-summary-row">
                    <span>Descuento</span>
                    <div class="discount-input-wrap">
                        <span class="discount-prefix">Q</span>
                        <input type="number"
                               id="cartDiscount"
                               value="0"
                               min="0"
                               step="0.01"
                               onchange="updateTotals()">
                    </div>
                </div>

                <div class="cart-summary-row cart-summary-total">
                    <span>Total a pagar</span>
                    <span id="cartTotal">Q0.00</span>
                </div>
            </div>

            <div class="cart-actions">
                <button class="btn btn-ghost" onclick="clearCart()">
                    <i class="fas fa-trash"></i>
                    <span>Cancelar</span>
                </button>
                <button class="btn btn-success" onclick="procesarVenta()" id="btnVender">
                    <i class="fas fa-check"></i>
                    <span>Finalizar</span>
                </button>
            </div>

        </div>

    </div>

</div>

{{-- ============================================
     MODAL DE ÉXITO
============================================ --}}
<div class="modal-overlay" id="successModal">
    <div class="modal-box">
        <div class="modal-icon modal-icon-success">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2 class="modal-title">¡Venta registrada!</h2>
        <p class="modal-desc">La transacción se completó correctamente</p>

        <div class="modal-info">
            <div class="modal-info-row">
                <span>Número de venta</span>
                <strong id="modalVentaId">#0000</strong>
            </div>
            <div class="modal-info-row">
                <span>Total cobrado</span>
                <strong id="modalTotal" class="modal-total">Q0.00</strong>
            </div>
        </div>

        <button class="btn btn-primary btn-block" onclick="closeSuccessModal()">
            <i class="fas fa-plus"></i>
            <span>Nueva venta</span>
        </button>
    </div>
</div>

{{-- ============================================
     MODAL DE ERROR
============================================ --}}
<div class="modal-overlay" id="errorModal">
    <div class="modal-box">
        <div class="modal-icon modal-icon-error">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h2 class="modal-title">Algo salió mal</h2>
        <p class="modal-desc" id="errorMessage"></p>
        <button class="btn btn-ghost btn-block" onclick="closeErrorModal()">
            <i class="fas fa-times"></i>
            <span>Cerrar</span>
        </button>
    </div>
</div>

@endsection

@section('styles')
<style>
    /* ============ LAYOUT POS ============ */
    .pos-layout {
        display: grid;
        grid-template-columns: 1fr 420px;
        gap: 20px;
        height: calc(100vh - 160px);
    }

    /* ============ COLUMNA IZQUIERDA ============ */
    .pos-products {
        display: flex;
        flex-direction: column;
        gap: 16px;
        min-height: 0;
    }

    /* Buscador */
    .pos-search { position: relative; }

    .search-input-wrap {
        position: relative;
    }

    .search-input-wrap i {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 15px;
        pointer-events: none;
    }

    .search-input-wrap input {
        width: 100%;
        padding: 15px 130px 15px 48px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        font-family: 'Inter', sans-serif;
        outline: none;
        background: #fff;
        transition: all 0.25s ease;
    }

    .search-input-wrap input:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16,185,129,0.12);
    }

    .search-hint {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 11px;
        color: #94a3b8;
        pointer-events: none;
    }

    .search-hint kbd {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 5px;
        padding: 2px 6px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        color: #475569;
        font-weight: 700;
    }

    .search-results {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 20px 40px -12px rgba(15,23,42,0.15);
        max-height: 340px;
        overflow-y: auto;
        z-index: 50;
        display: none;
    }

    .search-results.active { display: block; }

    .search-result-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 18px;
        cursor: pointer;
        transition: background 0.2s ease;
        border-bottom: 1px solid #f1f5f9;
    }

    .search-result-item:last-child { border-bottom: none; }
    .search-result-item:hover { background: #f0fdf4; }

    .search-result-info { display: flex; flex-direction: column; gap: 3px; }
    .search-result-name { font-size: 13px; font-weight: 700; color: #0f172a; }
    .search-result-stock { font-size: 11px; color: #64748b; }
    .search-result-price {
        font-family: 'Poppins', sans-serif;
        font-size: 15px;
        font-weight: 800;
        color: #059669;
    }

    /* Grid productos */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 12px;
        overflow-y: auto;
        padding-right: 8px;
        flex: 1;
    }

    .products-grid::-webkit-scrollbar { width: 6px; }
    .products-grid::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 3px; }

    .product-card {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .product-card:hover {
        border-color: #10b981;
        transform: translateY(-3px);
        box-shadow: 0 15px 30px -12px rgba(16,185,129,0.25);
    }

    .product-card:active {
        transform: translateY(-1px);
    }

    .product-card-icon {
        width: 42px;
        height: 42px;
        background: #ecfdf5;
        color: #059669;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }

    .product-card-body { flex: 1; }

    .product-card-name {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.3;
    }

    .product-card-cat {
        font-size: 10px;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .product-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 10px;
        border-top: 1px solid #f1f5f9;
    }

    .product-card-price {
        font-family: 'Poppins', sans-serif;
        font-size: 15px;
        font-weight: 800;
        color: #059669;
    }

    .product-card-stock {
        font-size: 10px;
        color: #64748b;
        font-weight: 600;
    }

    .empty-products {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    .empty-products i { font-size: 48px; color: #e2e8f0; margin-bottom: 16px; display: block; }
    .empty-products h3 { font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 6px; }
    .empty-products p { font-size: 13px; margin: 0; }

    /* ============ COLUMNA DERECHA ============ */
    .pos-cart {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    /* Cart header */
    .cart-header {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 20px;
        border-bottom: 1px solid #f1f5f9;
        background: #f8fafc;
    }

    .cart-header-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #059669, #10b981);
        color: #fff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        box-shadow: 0 6px 16px rgba(16,185,129,0.3);
    }

    .cart-header-info { flex: 1; }

    .cart-header-title {
        font-family: 'Poppins', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .cart-header-sub {
        font-size: 12px;
        color: #64748b;
    }

    /* Cart body */
    .cart-body {
        flex: 1;
        overflow-y: auto;
        padding: 12px;
    }

    .cart-body::-webkit-scrollbar { width: 6px; }
    .cart-body::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 3px; }

    .cart-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        text-align: center;
        color: #94a3b8;
        padding: 40px 20px;
    }

    .cart-empty i {
        font-size: 56px;
        color: #e2e8f0;
        margin-bottom: 16px;
    }

    .cart-empty h4 {
        font-family: 'Poppins', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .cart-empty p { font-size: 12px; margin: 0; }

    /* Cart items */
    .cart-item {
        display: flex;
        gap: 10px;
        padding: 12px;
        background: #f8fafc;
        border-radius: 12px;
        margin-bottom: 8px;
        transition: background 0.2s ease;
        animation: slideIn 0.25s ease;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-10px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .cart-item:hover { background: #f1f5f9; }

    .cart-item-body { flex: 1; min-width: 0; }

    .cart-item-name {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cart-item-price {
        font-size: 11px;
        color: #64748b;
        margin-bottom: 8px;
    }

    .cart-item-qty {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .qty-btn {
        width: 26px;
        height: 26px;
        border: 1px solid #e2e8f0;
        background: #fff;
        border-radius: 7px;
        cursor: pointer;
        font-size: 11px;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .qty-btn:hover {
        background: #10b981;
        color: #fff;
        border-color: #10b981;
    }

    .cart-item-qty input {
        width: 46px;
        text-align: center;
        padding: 4px;
        border: 1px solid #e2e8f0;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 700;
        outline: none;
        font-family: 'Inter', sans-serif;
    }

    .cart-item-qty input:focus {
        border-color: #10b981;
    }

    .cart-item-total {
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        font-weight: 800;
        color: #059669;
        text-align: right;
        min-width: 70px;
        align-self: center;
    }

    .cart-item-remove {
        width: 26px;
        height: 26px;
        background: #fef2f2;
        border: none;
        border-radius: 7px;
        color: #dc2626;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        align-self: flex-start;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .cart-item-remove:hover {
        background: #dc2626;
        color: #fff;
    }

    /* ============ FOOTER CARRITO ============ */
    .cart-footer {
        padding: 20px;
        border-top: 1px solid #f1f5f9;
        background: #f8fafc;
    }

    .cart-summary { margin-bottom: 16px; }

    .cart-summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        font-size: 13px;
        color: #475569;
    }

    .cart-summary-row > span:last-child {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        color: #0f172a;
    }

    /* Discount input */
    .discount-input-wrap {
        position: relative;
        display: inline-block;
    }

    .discount-prefix {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        pointer-events: none;
    }

    .discount-input-wrap input {
        width: 110px;
        padding: 7px 10px 7px 28px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        text-align: right;
        font-size: 13px;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
        outline: none;
        background: #fff;
        color: #0f172a;
        transition: all 0.2s ease;
    }

    .discount-input-wrap input:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16,185,129,0.1);
    }

    .cart-summary-total {
        padding-top: 14px;
        border-top: 2px solid #e2e8f0;
        margin-top: 8px;
        font-size: 14px !important;
        font-weight: 700;
        color: #0f172a !important;
    }

    .cart-summary-total span:last-child {
        font-size: 22px !important;
        color: #059669 !important;
        font-weight: 800 !important;
    }

    /* Actions */
    .cart-actions {
        display: grid;
        grid-template-columns: 1fr 1.6fr;
        gap: 10px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 13px 20px;
        border-radius: 11px;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .btn-ghost {
        background: #fff;
        color: #475569;
        border: 1.5px solid #e2e8f0;
    }

    .btn-ghost:hover {
        background: #f1f5f9;
    }

    .btn-success {
        background: linear-gradient(135deg, #059669, #10b981);
        color: #fff;
        box-shadow: 0 6px 16px rgba(5,150,105,0.3);
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(5,150,105,0.45);
    }

    .btn-success:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2563eb, #0ea5e9);
        color: #fff;
        box-shadow: 0 6px 16px rgba(37,99,235,0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(37,99,235,0.45);
    }

    .btn-block { width: 100%; }

    /* ============ MODALES ============ */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15,23,42,0.6);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-overlay.active { display: flex; }

    .modal-box {
        background: #fff;
        border-radius: 20px;
        padding: 40px 32px;
        max-width: 400px;
        width: 100%;
        text-align: center;
        animation: popIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes popIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }

    .modal-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        margin: 0 auto 20px;
    }

    .modal-icon-success {
        background: #ecfdf5;
        color: #059669;
    }

    .modal-icon-error {
        background: #fef2f2;
        color: #dc2626;
    }

    .modal-title {
        font-family: 'Poppins', sans-serif;
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .modal-desc {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 24px;
        line-height: 1.5;
    }

    .modal-info {
        background: #f8fafc;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 24px;
    }

    .modal-info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        font-size: 14px;
    }

    .modal-info-row span { color: #64748b; }
    .modal-info-row strong { color: #0f172a; font-weight: 800; font-family: 'Poppins', sans-serif; }
    .modal-total { color: #059669 !important; font-size: 18px; }

    /* ============ RESPONSIVE ============ */
    @media (max-width: 1100px) {
        .pos-layout {
            grid-template-columns: 1fr;
            height: auto;
        }
        .pos-cart {
            position: sticky;
            bottom: 0;
        }
    }

    @media (max-width: 640px) {
        .search-hint { display: none; }
        .search-input-wrap input { padding-right: 20px; }
        .cart-actions { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('scripts')
<script>
    // ==================================================
    // ESTADO DEL CARRITO
    // ==================================================
    let cart = [];

    // ==================================================
    // AGREGAR PRODUCTO
    // ==================================================
    function agregarProducto(id, nombre, precio, stock) {
        const existente = cart.find(item => item.id === id);

        if (existente) {
            if (existente.cantidad >= stock) {
                showError(`Stock máximo alcanzado para "${nombre}". Disponible: ${stock}`);
                return;
            }
            existente.cantidad++;
        } else {
            cart.push({
                id: id,
                nombre: nombre,
                precio: parseFloat(precio),
                stock: parseInt(stock),
                cantidad: 1
            });
        }

        renderCart();
    }

    // ==================================================
    // RENDERIZAR CARRITO
    // ==================================================
    function renderCart() {
        const cartBody = document.getElementById('cartBody');
        const cartCount = document.getElementById('cartCount');

        if (cart.length === 0) {
            cartBody.innerHTML = `
                <div class="cart-empty">
                    <i class="fas fa-shopping-basket"></i>
                    <h4>Carrito vacío</h4>
                    <p>Agrega productos para comenzar la venta</p>
                </div>
            `;
            cartCount.textContent = '0 productos';
            updateTotals();
            return;
        }

        let html = '';
        cart.forEach((item, index) => {
            const total = item.precio * item.cantidad;
            html += `
                <div class="cart-item">
                    <div class="cart-item-body">
                        <div class="cart-item-name">${item.nombre}</div>
                        <div class="cart-item-price">Q${item.precio.toFixed(2)} × ${item.cantidad}</div>
                        <div class="cart-item-qty">
                            <button class="qty-btn" onclick="cambiarCantidad(${index}, -1)">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number"
                                   value="${item.cantidad}"
                                   min="1"
                                   max="${item.stock}"
                                   onchange="setCantidad(${index}, this.value)">
                            <button class="qty-btn" onclick="cambiarCantidad(${index}, 1)">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="cart-item-total">Q${total.toFixed(2)}</div>
                    <button class="cart-item-remove" onclick="eliminarItem(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
        });

        cartBody.innerHTML = html;

        const totalItems = cart.reduce((sum, item) => sum + item.cantidad, 0);
        cartCount.textContent = totalItems + ' producto' + (totalItems !== 1 ? 's' : '');

        updateTotals();
    }

    // ==================================================
    // CAMBIAR CANTIDAD
    // ==================================================
    function cambiarCantidad(index, delta) {
        const item = cart[index];
        const nueva = item.cantidad + delta;

        if (nueva < 1) return;

        if (nueva > item.stock) {
            showError(`Stock máximo: ${item.stock} unidades`);
            return;
        }

        item.cantidad = nueva;
        renderCart();
    }

    // ==================================================
    // SET CANTIDAD DIRECTA
    // ==================================================
    function setCantidad(index, valor) {
        const item = cart[index];
        const num = parseInt(valor) || 1;

        if (num < 1) {
            item.cantidad = 1;
        } else if (num > item.stock) {
            showError(`Stock máximo: ${item.stock} unidades`);
            item.cantidad = item.stock;
        } else {
            item.cantidad = num;
        }

        renderCart();
    }

    // ==================================================
    // ELIMINAR ITEM
    // ==================================================
    function eliminarItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    // ==================================================
    // ACTUALIZAR TOTALES
    // ==================================================
    function updateTotals() {
        const subtotal = cart.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
        const descuento = parseFloat(document.getElementById('cartDiscount').value) || 0;
        const total = Math.max(0, subtotal - descuento);

        document.getElementById('cartSubtotal').textContent = 'Q' + subtotal.toFixed(2);
        document.getElementById('cartTotal').textContent = 'Q' + total.toFixed(2);
    }

    // ==================================================
    // LIMPIAR CARRITO
    // ==================================================
    function clearCart() {
        if (cart.length === 0) return;

        if (confirm('¿Cancelar la venta actual?')) {
            cart = [];
            document.getElementById('cartDiscount').value = 0;
            renderCart();
        }
    }

    // ==================================================
    // PROCESAR VENTA
    // ==================================================
    async function procesarVenta() {
        if (cart.length === 0) {
            showError('Agrega al menos un producto a la venta');
            return;
        }

        const btn = document.getElementById('btnVender');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

        try {
            const response = await fetch('{{ route("vendedor.pos.procesar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    items: cart.map(item => ({
                        idProducto: item.id,
                        cantidad: item.cantidad
                    })),
                    descuento: parseFloat(document.getElementById('cartDiscount').value) || 0,
                }),
            });

            const data = await response.json();

            if (data.success) {
                document.getElementById('modalVentaId').textContent =
                    '#' + String(data.idVenta).padStart(4, '0');
                document.getElementById('modalTotal').textContent =
                    'Q' + parseFloat(data.total).toFixed(2);
                document.getElementById('successModal').classList.add('active');

                cart = [];
                document.getElementById('cartDiscount').value = 0;
                renderCart();
            } else {
                showError(data.mensaje);
            }
        } catch (error) {
            showError('Error de conexión. Intenta de nuevo.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> <span>Finalizar</span>';
        }
    }

    // ==================================================
    // MODALES
    // ==================================================
    function showError(msg) {
        document.getElementById('errorMessage').textContent = msg;
        document.getElementById('errorModal').classList.add('active');
    }

    function closeErrorModal() {
        document.getElementById('errorModal').classList.remove('active');
    }

    function closeSuccessModal() {
        document.getElementById('successModal').classList.remove('active');
        window.location.reload();
    }

    // ==================================================
    // BÚSQUEDA EN TIEMPO REAL
    // ==================================================
    const buscarInput = document.getElementById('buscarInput');
    const searchResults = document.getElementById('searchResults');
    let timeoutId;

    buscarInput.addEventListener('input', function () {
        clearTimeout(timeoutId);
        const q = this.value.trim();

        if (q.length < 2) {
            searchResults.classList.remove('active');
            return;
        }

        timeoutId = setTimeout(async () => {
            try {
                const response = await fetch(
                    `{{ route("vendedor.pos.buscar") }}?q=${encodeURIComponent(q)}`
                );
                const data = await response.json();

                if (data.length > 0) {
                    let html = '';
                    data.forEach(p => {
                        html += `
                            <div class="search-result-item"
                                 onclick="seleccionarResultado(${p.idProducto}, '${p.nombre.replace(/'/g, "\\'")}', ${p.precio}, ${p.stock_actual})">
                                <div class="search-result-info">
                                    <span class="search-result-name">${p.nombre}</span>
                                    <span class="search-result-stock">
                                        Stock: ${p.stock_actual} · ${p.categoria}
                                    </span>
                                </div>
                                <span class="search-result-price">
                                    Q${parseFloat(p.precio).toFixed(2)}
                                </span>
                            </div>
                        `;
                    });
                    searchResults.innerHTML = html;
                    searchResults.classList.add('active');
                } else {
                    searchResults.classList.remove('active');
                }
            } catch (error) {
                console.error(error);
            }
        }, 250);
    });

    function seleccionarResultado(id, nombre, precio, stock) {
        agregarProducto(id, nombre, precio, stock);
        buscarInput.value = '';
        searchResults.classList.remove('active');
        buscarInput.focus();
    }

    // Cerrar resultados al hacer clic fuera
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.pos-search')) {
            searchResults.classList.remove('active');
        }
    });

    // ==================================================
    // ATAJOS DE TECLADO
    // ==================================================
    document.addEventListener('keydown', function (e) {
        // F2 → Enfocar buscador
        if (e.key === 'F2') {
            e.preventDefault();
            buscarInput.focus();
        }

        // Esc → Cerrar resultados
        if (e.key === 'Escape') {
            searchResults.classList.remove('active');
            closeErrorModal();
        }

        // Enter en buscador → Agregar primer resultado
        if (e.key === 'Enter' && document.activeElement === buscarInput) {
            const firstResult = searchResults.querySelector('.search-result-item');
            if (firstResult) {
                firstResult.click();
            }
        }
    });

    // ==================================================
    // INICIALIZAR
    // ==================================================
    renderCart();
</script>
@endsection