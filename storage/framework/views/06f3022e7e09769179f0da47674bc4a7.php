

<?php $__env->startSection('title', 'Ventas'); ?>
<?php $__env->startSection('page-title', 'Historial de Ventas'); ?>
<?php $__env->startSection('page-subtitle', 'Todas las transacciones realizadas en el sistema'); ?>

<?php $__env->startSection('content'); ?>


<?php if(session('success')): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span><?php echo e(session('success')); ?></span>
    </div>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <span><?php echo e($errors->first()); ?></span>
    </div>
<?php endif; ?>


<div class="stats-mini">
    <div class="stat-mini">
        <div class="stat-mini-icon" style="background: linear-gradient(135deg, #2563eb, #0ea5e9);">
            <i class="fas fa-receipt"></i>
        </div>
        <div class="stat-mini-body">
            <div class="stat-mini-value"><?php echo e($ventas->total()); ?></div>
            <div class="stat-mini-label">Total ventas</div>
        </div>
    </div>

    <div class="stat-mini">
        <div class="stat-mini-icon" style="background: linear-gradient(135deg, #059669, #10b981);">
            <i class="fas fa-coins"></i>
        </div>
        <div class="stat-mini-body">
            <div class="stat-mini-value">
                Q<?php echo e(number_format($ventas->where('estado', 'Activa')->sum('total'), 2)); ?>

            </div>
            <div class="stat-mini-label">Recaudado (página)</div>
        </div>
    </div>

    <div class="stat-mini">
        <div class="stat-mini-icon" style="background: linear-gradient(135deg, #dc2626, #ef4444);">
            <i class="fas fa-ban"></i>
        </div>
        <div class="stat-mini-body">
            <div class="stat-mini-value"><?php echo e($ventas->where('estado', 'Anulada')->count()); ?></div>
            <div class="stat-mini-label">Anuladas (página)</div>
        </div>
    </div>
</div>


<div class="card">

    
    <div class="card-head">
        <form method="GET" class="filters">

            <div class="filter-input">
                <i class="fas fa-search filter-icon"></i>
                <input type="text"
                       name="buscar"
                       placeholder="Buscar por # de venta..."
                       value="<?php echo e(request('buscar')); ?>">
            </div>

            <select name="vendedor" class="filter-select">
                <option value="">Todos los vendedores</option>
                <?php $__currentLoopData = $vendedores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($v->id); ?>"
                        <?php echo e(request('vendedor') == $v->id ? 'selected' : ''); ?>>
                        <?php echo e($v->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <input type="date" name="fecha" class="filter-date" value="<?php echo e(request('fecha')); ?>">

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
                <span>Filtrar</span>
            </button>

            <?php if(request()->hasAny(['buscar', 'vendedor', 'fecha'])): ?>
                <a href="<?php echo e(route('admin.ventas.index')); ?>" class="btn btn-ghost">
                    <i class="fas fa-times"></i>
                    <span>Limpiar</span>
                </a>
            <?php endif; ?>
        </form>
    </div>

    
    <div class="info-bar">
        <div class="info-item">
            <i class="fas fa-receipt"></i>
            <strong><?php echo e($ventas->total()); ?></strong> ventas encontradas
        </div>
        <?php if(request('buscar') || request('vendedor') || request('fecha')): ?>
            <div class="info-item info-item-filtered">
                <i class="fas fa-filter"></i>
                Filtros aplicados
            </div>
        <?php endif; ?>
    </div>

    
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 120px;">#Venta</th>
                    <th style="width: 180px;">Fecha</th>
                    <th>Vendedor</th>
                    <th style="width: 130px;">Subtotal</th>
                    <th style="width: 130px;">Descuento</th>
                    <th style="width: 130px;">Total</th>
                    <th style="width: 120px;">Estado</th>
                    <th style="width: 160px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $ventas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="<?php echo e($venta->estado === 'Anulada' ? 'row-annulled' : ''); ?>">
                        
                        <td>
                            <span class="table-id">
                                #<?php echo e(str_pad($venta->idVenta, 4, '0', STR_PAD_LEFT)); ?>

                            </span>
                        </td>

                        
                        <td>
                            <div class="date-cell">
                                <div class="date-icon">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div>
                                    <div class="date-main">
                                        <?php echo e(\Carbon\Carbon::parse($venta->created_at)->format('d/m/Y')); ?>

                                    </div>
                                    <div class="date-sub">
                                        <?php echo e(\Carbon\Carbon::parse($venta->created_at)->format('H:i')); ?> hrs
                                    </div>
                                </div>
                            </div>
                        </td>

                        
                        <td>
                            <div class="seller-cell">
                                <div class="seller-avatar">
                                    <?php echo e(strtoupper(substr($venta->vendedor, 0, 2))); ?>

                                </div>
                                <span class="seller-name"><?php echo e($venta->vendedor); ?></span>
                            </div>
                        </td>

                        
                        <td>
                            <span class="price-normal">
                                Q<?php echo e(number_format($venta->subtotal, 2)); ?>

                            </span>
                        </td>

                        
                        <td>
                            <?php if($venta->descuento > 0): ?>
                                <span class="price-discount">
                                    -Q<?php echo e(number_format($venta->descuento, 2)); ?>

                                </span>
                            <?php else: ?>
                                <span class="price-empty">—</span>
                            <?php endif; ?>
                        </td>

                        
                        <td>
                            <span class="price-total">
                                Q<?php echo e(number_format($venta->total, 2)); ?>

                            </span>
                        </td>

                        
                        <td>
                            <?php if($venta->estado === 'Activa'): ?>
                                <span class="badge badge-green">
                                    <i class="fas fa-circle" style="font-size: 6px;"></i>
                                    Activa
                                </span>
                            <?php else: ?>
                                <span class="badge badge-red">
                                    <i class="fas fa-circle" style="font-size: 6px;"></i>
                                    Anulada
                                </span>
                            <?php endif; ?>
                        </td>

                        
                        <td>
                            <div class="table-actions">
                                <a href="<?php echo e(route('admin.ventas.show', $venta->idVenta)); ?>"
                                   class="btn-icon" title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <?php if($venta->estado === 'Activa'): ?>
                                    <form action="<?php echo e(route('admin.ventas.destroy', $venta->idVenta)); ?>"
                                          method="POST"
                                          style="display:inline"
                                          onsubmit="return confirm('¿Anular la venta #<?php echo e(str_pad($venta->idVenta, 4, '0', STR_PAD_LEFT)); ?>?\n\nEl stock de los productos será restaurado.')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn-icon btn-icon-danger" title="Anular venta">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <h4 class="empty-title">No hay ventas registradas</h4>
                                <p class="empty-desc">
                                    Las ventas aparecerán aquí una vez se realicen desde el punto de venta
                                </p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <?php if($ventas->hasPages()): ?>
        <div class="pagination">
            <?php echo e($ventas->appends(request()->query())->links()); ?>

        </div>
    <?php endif; ?>

</div>

<style>
    /* Alerts */
    .alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 13px;
        font-weight: 500;
    }
    .alert-success { background: #ecfdf5; border-left: 4px solid #10b981; color: #065f46; }
    .alert-error { background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; }
    .alert i { font-size: 16px; }

    /* Stats mini */
    .stats-mini {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    .stat-mini {
        display: flex;
        align-items: center;
        gap: 16px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px;
        transition: all 0.3s ease;
    }

    .stat-mini:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px -10px rgba(15, 23, 42, 0.1);
    }

    .stat-mini-icon {
        width: 48px;
        height: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 19px;
        flex-shrink: 0;
        box-shadow: 0 8px 20px -6px rgba(37, 99, 235, 0.3);
    }

    .stat-mini-value {
        font-family: 'Poppins', sans-serif;
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        letter-spacing: -0.8px;
        margin-bottom: 4px;
    }

    .stat-mini-label {
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    /* Card */
    .card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
    }

    .card-head {
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
    }

    /* Filtros */
    .filters {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-input {
        position: relative;
        flex: 1;
        min-width: 200px;
    }

    .filter-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
        pointer-events: none;
    }

    .filter-input input,
    .filter-select,
    .filter-date {
        width: 100%;
        padding: 11px 14px 11px 40px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13px;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: all 0.25s ease;
        color: #0f172a;
        background: #ffffff;
    }

    .filter-select,
    .filter-date {
        padding-left: 14px;
        min-width: 180px;
        cursor: pointer;
    }

    .filter-select { appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3e%3cpath fill='%2394a3b8' d='M6 8L0 0h12z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 36px;
    }

    .filter-input input:focus,
    .filter-select:focus,
    .filter-date:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    /* Info bar */
    .info-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 12px 24px;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
    }

    .info-item i { color: #94a3b8; font-size: 12px; }
    .info-item strong { color: #0f172a; font-weight: 700; }

    .info-item-filtered {
        padding: 5px 12px;
        background: #eff6ff;
        color: #1d4ed8;
        border-radius: 100px;
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
    }

    .info-item-filtered i { color: #3b82f6; }

    /* Tabla */
    .table-wrap { overflow-x: auto; }

    .table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1100px;
    }

    .table thead { background: #f8fafc; }

    .table th {
        padding: 14px 20px;
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .table td {
        padding: 16px 20px;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .table tbody tr { transition: background 0.2s ease; }
    .table tbody tr:hover { background: #f8fafc; }
    .table tbody tr:last-child td { border-bottom: none; }

    .row-annulled {
        opacity: 0.7;
    }

    .row-annulled .price-total {
        text-decoration: line-through;
        color: #94a3b8 !important;
    }

    /* #Venta */
    .table-id {
        display: inline-block;
        padding: 6px 12px;
        background: #eff6ff;
        color: #1d4ed8;
        border-radius: 8px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        font-weight: 700;
    }

    /* Fecha */
    .date-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .date-icon {
        width: 36px;
        height: 36px;
        background: #f1f5f9;
        color: #64748b;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .date-main {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
    }

    .date-sub {
        font-size: 11px;
        color: #94a3b8;
    }

    /* Vendedor */
    .seller-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .seller-avatar {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 800;
        flex-shrink: 0;
    }

    .seller-name {
        font-size: 13px;
        font-weight: 600;
        color: #0f172a;
    }

    /* Precios */
    .price-normal {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
    }

    .price-discount {
        display: inline-block;
        padding: 3px 10px;
        background: #fef2f2;
        color: #dc2626;
        border-radius: 100px;
        font-size: 12px;
        font-weight: 700;
    }

    .price-empty {
        color: #cbd5e1;
    }

    .price-total {
        font-family: 'Poppins', sans-serif;
        font-size: 15px;
        font-weight: 800;
        color: #059669;
    }

    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        font-size: 11px;
        font-weight: 700;
        border-radius: 100px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-green { background: #ecfdf5; color: #059669; }
    .badge-red { background: #fef2f2; color: #dc2626; }
    .badge-green i, .badge-red i { font-size: 6px; }

    /* Acciones */
    .table-actions { display: flex; gap: 6px; }

    .btn-icon {
        width: 34px;
        height: 34px;
        background: #f1f5f9;
        border: none;
        border-radius: 9px;
        color: #475569;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none;
        font-size: 13px;
    }

    .btn-icon:hover {
        background: #2563eb;
        color: #ffffff;
        transform: translateY(-2px);
    }

    .btn-icon-danger:hover { background: #dc2626; }

    /* Botones */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 20px;
        border-radius: 10px;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.25s ease;
        white-space: nowrap;
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

    /* Empty state */
    .empty-state { padding: 60px 24px; text-align: center; }

    .empty-icon {
        width: 80px;
        height: 80px;
        background: #f1f5f9;
        color: #94a3b8;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin: 0 auto 20px;
    }

    .empty-title {
        font-family: 'Poppins', sans-serif;
        font-size: 17px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .empty-desc {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 24px;
    }

    /* Pagination */
    .pagination {
        padding: 20px 24px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: center;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .stats-mini { grid-template-columns: 1fr; }
    }

    @media (max-width: 640px) {
        .card-head { padding: 16px; }
        .table th, .table td { padding: 12px 14px; }
        .info-bar { padding: 10px 16px; }
        .filters { flex-direction: column; width: 100%; }
        .filter-input, .filter-select, .filter-date { width: 100%; }
        .btn { width: 100%; justify-content: center; }
    }
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tienda\resources\views/admin/ventas/index.blade.php ENDPATH**/ ?>