

<?php $__env->startSection('title', 'Mis Ventas'); ?>
<?php $__env->startSection('page-title', 'Mis Ventas'); ?>
<?php $__env->startSection('page-subtitle', 'Historial de tus ventas realizadas'); ?>

<?php $__env->startSection('content'); ?>

<div class="stats-mini">
    <div class="stat">
        <div class="stat-icon" style="background: linear-gradient(135deg, #059669, #10b981);"><i class="fas fa-receipt"></i></div>
        <div>
            <div class="stat-value"><?php echo e($stats['hoy']); ?></div>
            <div class="stat-label">Ventas hoy</div>
        </div>
    </div>
    <div class="stat">
        <div class="stat-icon" style="background: linear-gradient(135deg, #0891b2, #06b6d4);"><i class="fas fa-calendar"></i></div>
        <div>
            <div class="stat-value"><?php echo e($stats['mes']); ?></div>
            <div class="stat-label">Ventas del mes</div>
        </div>
    </div>
    <div class="stat">
        <div class="stat-icon" style="background: linear-gradient(135deg, #2563eb, #0ea5e9);"><i class="fas fa-coins"></i></div>
        <div>
            <div class="stat-value">Q<?php echo e(number_format($stats['monto_mes'], 2)); ?></div>
            <div class="stat-label">Recaudado este mes</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-head">
        <form method="GET" class="filters-form">
            <input type="date" name="fecha" value="<?php echo e(request('fecha')); ?>">
            <button class="btn btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
            <?php if(request('fecha')): ?>
                <a href="<?php echo e(route('vendedor.ventas.index')); ?>" class="btn btn-ghost">Limpiar</a>
            <?php endif; ?>
        </form>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>#Venta</th>
                <th>Fecha</th>
                <th>Productos</th>
                <th style="text-align: right;">Total</th>
                <th>Estado</th>
                <th style="text-align: center;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $ventas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><span class="table-id">#<?php echo e(str_pad($v->idVenta, 4, '0', STR_PAD_LEFT)); ?></span></td>
                    <td><?php echo e(\Carbon\Carbon::parse($v->created_at)->format('d/m/Y H:i')); ?></td>
                    <td>—</td>
                    <td style="text-align: right;"><strong style="color: #059669;">Q<?php echo e(number_format($v->total, 2)); ?></strong></td>
                    <td>
                        <?php if($v->estado === 'Activa'): ?>
                            <span class="badge badge-green">Activa</span>
                        <?php else: ?>
                            <span class="badge badge-red">Anulada</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <a href="<?php echo e(route('vendedor.ventas.show', $v->idVenta)); ?>" class="btn-icon">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="empty">Aún no has realizado ventas</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination-wrap"><?php echo e($ventas->appends(request()->query())->links()); ?></div>
</div>

<style>
    .stats-mini { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 20px; }
    .stat { display: flex; align-items: center; gap: 16px; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; }
    .stat-icon { width: 48px; height: 48px; border-radius: 13px; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 19px; }
    .stat-value { font-family: 'Poppins', sans-serif; font-size: 20px; font-weight: 800; color: #0f172a; }
    .stat-label { font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }

    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; }
    .card-head { padding: 16px 20px; border-bottom: 1px solid #f1f5f9; }
    .filters-form { display: flex; gap: 10px; flex-wrap: wrap; }
    .filters-form input { padding: 11px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 13px; outline: none; }

    .table { width: 100%; border-collapse: collapse; }
    .table th { background: #f8fafc; padding: 14px 20px; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; text-align: left; border-bottom: 1px solid #e2e8f0; }
    .table td { padding: 16px 20px; font-size: 13px; border-bottom: 1px solid #f1f5f9; }
    .table tbody tr:hover { background: #f8fafc; }

    .table-id { padding: 4px 10px; background: #ecfdf5; color: #059669; border-radius: 8px; font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 700; }
    .badge { display: inline-block; padding: 4px 10px; font-size: 10px; font-weight: 700; border-radius: 100px; text-transform: uppercase; letter-spacing: 0.5px; }
    .badge-green { background: #ecfdf5; color: #059669; }
    .badge-red { background: #fef2f2; color: #dc2626; }
    .btn-icon { width: 34px; height: 34px; background: #f1f5f9; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; color: #475569; text-decoration: none; transition: all 0.2s ease; }
    .btn-icon:hover { background: #10b981; color: #fff; }

    .empty { text-align: center; padding: 40px !important; color: #94a3b8; }
    .pagination-wrap { padding: 20px; display: flex; justify-content: center; }

    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 11px 18px; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; }
    .btn-primary { background: linear-gradient(135deg, #059669, #10b981); color: #fff; }
    .btn-ghost { background: #f1f5f9; color: #475569; }
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vendedor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tienda\resources\views/vendedor/ventas/index.blade.php ENDPATH**/ ?>