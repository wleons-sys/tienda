

<?php $__env->startSection('title', 'Usuarios'); ?>
<?php $__env->startSection('page-title', 'Gestión de Usuarios'); ?>
<?php $__env->startSection('page-subtitle', 'Administra los usuarios del sistema'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> <?php echo e($errors->first()); ?>

    </div>
<?php endif; ?>

<div class="card">
    <div class="card-head">
        <form method="GET" class="filters">
            <input type="text" name="buscar" placeholder="Buscar por nombre o correo..." value="<?php echo e(request('buscar')); ?>">
            <select name="rol">
                <option value="">Todos los roles</option>
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($rol->idRol); ?>" <?php echo e(request('rol') == $rol->idRol ? 'selected' : ''); ?>>
                        <?php echo e($rol->nombre); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Filtrar
            </button>
            <a href="<?php echo e(route('admin.usuarios.index')); ?>" class="btn btn-ghost">Limpiar</a>
        </form>

        <a href="<?php echo e(route('admin.usuarios.create')); ?>" class="btn btn-primary">
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
            <?php $__empty_1 = true; $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $rol = DB::table('roles_usuarios')
                        ->join('roles', 'roles_usuarios.idRol', '=', 'roles.idRol')
                        ->where('roles_usuarios.idUsuario', $u->id)
                        ->select('roles.nombre')
                        ->first();
                ?>
                <tr>
                    <td>#<?php echo e(str_pad($u->id, 4, '0', STR_PAD_LEFT)); ?></td>
                    <td><strong><?php echo e($u->name); ?></strong></td>
                    <td><?php echo e($u->email); ?></td>
                    <td>
                        <span class="badge <?php echo e($rol && $rol->nombre === 'Administrador' ? 'badge-blue' : 'badge-green'); ?>">
                            <?php echo e($rol->nombre ?? 'Sin rol'); ?>

                        </span>
                    </td>
                    <td>
                        <span class="badge <?php echo e($u->activo ? 'badge-green' : 'badge-red'); ?>">
                            <?php echo e($u->activo ? 'Activo' : 'Inactivo'); ?>

                        </span>
                    </td>
                    <td class="actions">
                        <a href="<?php echo e(route('admin.usuarios.show', $u->id)); ?>" class="btn-icon" title="Ver">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="<?php echo e(route('admin.usuarios.edit', $u->id)); ?>" class="btn-icon" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="<?php echo e(route('admin.usuarios.destroy', $u->id)); ?>" method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar usuario?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn-icon btn-icon-danger" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="empty">No hay usuarios registrados</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php echo e($usuarios->links()); ?>

    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tienda\resources\views/admin/usuarios/index.blade.php ENDPATH**/ ?>