<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Panel Vendedor'); ?> | Multiservicios León</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #0f172a;
        }

        h1, h2, h3, h4 { font-family: 'Poppins', sans-serif; }

        /* ============ LAYOUT ============ */
        .layout { display: flex; min-height: 100vh; }

        /* ============ SIDEBAR ============ */
        .sidebar {
            width: 260px;
            background: #0f172a;
            color: #fff;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 50;
        }

        .sb-logo {
            padding: 22px;
            display: flex;
            align-items: center;
            gap: 11px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            text-decoration: none;
        }

        .sb-logo-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            box-shadow: 0 6px 16px rgba(16,185,129,0.4);
            flex-shrink: 0;
        }

        .sb-logo-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .sb-logo-text span:first-child {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: #fff;
        }

        .sb-logo-text span:last-child {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: #34d399;
            margin-top: 2px;
        }

        .sb-user-card {
            padding: 20px 22px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sb-avatar {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            flex-shrink: 0;
        }

        .sb-user-info { flex: 1; min-width: 0; }

        .sb-user-name {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sb-user-role {
            font-size: 11px;
            color: #34d399;
            font-weight: 500;
            margin-top: 2px;
        }

        .sb-user-badge {
            display: inline-block;
            font-size: 10px;
            font-weight: 600;
            color: #fbbf24;
            background: rgba(251,191,36,0.1);
            padding: 2px 8px;
            border-radius: 100px;
            margin-top: 4px;
        }

        .sb-menu {
            flex: 1;
            padding: 18px 14px;
            overflow-y: auto;
        }

        .sb-group-title {
            font-size: 10px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 12px 14px 6px;
        }

        .sb-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 10px;
            color: #cbd5e1;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.25s ease;
            margin-bottom: 2px;
            position: relative;
        }

        .sb-link-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sb-link i {
            width: 20px;
            font-size: 15px;
            text-align: center;
            color: #64748b;
            transition: color 0.25s ease;
        }

        .sb-link:hover {
            background: rgba(255,255,255,0.05);
            color: #fff;
        }

        .sb-link:hover i { color: #10b981; }

        .sb-link.active {
            background: linear-gradient(135deg, rgba(16,185,129,0.2), rgba(5,150,105,0.1));
            color: #fff;
            font-weight: 600;
        }

        .sb-link.active i { color: #34d399; }

        .sb-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 20px;
            background: #10b981;
            border-radius: 0 3px 3px 0;
        }

        .sb-badge {
            background: #10b981;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 100px;
            min-width: 22px;
            text-align: center;
        }

        .sb-footer {
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }

        .sb-logout {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 14px;
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.2);
            color: #fca5a5;
            font-size: 13px;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.25s ease;
            width: 100%;
            justify-content: center;
        }

        .sb-logout:hover {
            background: #dc2626;
            border-color: #dc2626;
            color: #fff;
        }

        /* ============ MAIN ============ */
        .main {
            flex: 1;
            margin-left: 260px;
            min-width: 0;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 20px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .topbar-title {
            font-family: 'Poppins', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .topbar-sub {
            font-size: 13px;
            color: #64748b;
            margin-top: 3px;
        }

        .topbar-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #047857;
            font-size: 12px;
            font-weight: 600;
            border-radius: 100px;
        }

        .topbar-badge i { color: #10b981; font-size: 12px; }

        /* Content */
        .content { padding: 28px 32px; }

        /* ============ TOGGLE MOBILE ============ */
        .sb-toggle {
            display: none;
            position: fixed;
            top: 18px;
            left: 18px;
            z-index: 60;
            width: 42px;
            height: 42px;
            background: #0f172a;
            color: #fff;
            border: none;
            border-radius: 11px;
            cursor: pointer;
            font-size: 17px;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 900px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .sb-toggle { display: flex; }
            .topbar { padding-left: 72px; }
        }

        @media (max-width: 640px) {
            .content { padding: 20px; }
            .topbar { padding: 14px 20px 14px 72px; }
            .topbar-title { font-size: 18px; }
            .topbar-badge { display: none; }
        }
    </style>
    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body>

<!-- Toggle (móvil) -->
<button class="sb-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>

<div class="layout">

    <!-- ============ SIDEBAR ============ -->
    <aside class="sidebar" id="sidebar">

        <!-- Logo -->
        <a href="/" class="sb-logo">
            <div class="sb-logo-icon"><i class="fas fa-cubes"></i></div>
            <div class="sb-logo-text">
                <span>Multiservicios</span>
                <span>León</span>
            </div>
        </a>

        <!-- User -->
        <div class="sb-user-card">
            <div class="sb-avatar">
                <?php echo e(strtoupper(substr(auth()->user()->name, 0, 2))); ?>

            </div>
            <div class="sb-user-info">
                <div class="sb-user-name"><?php echo e(auth()->user()->name); ?></div>
                <div class="sb-user-role">Vendedor</div>
                <span class="sb-user-badge">
                    <i class="fas fa-user" style="font-size: 8px;"></i> Acceso vendedor
                </span>
            </div>
        </div>

        <!-- Menu -->
        <nav class="sb-menu">

            <div class="sb-group-title">Principal</div>
            <a href="<?php echo e(route('vendedor.dashboard')); ?>"
               class="sb-link <?php echo e(request()->routeIs('vendedor.dashboard') ? 'active' : ''); ?>">
                <div class="sb-link-left">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </div>
            </a>

            <div class="sb-group-title">Operaciones</div>
            <a href="<?php echo e(route('vendedor.pos.index')); ?>"
               class="sb-link <?php echo e(request()->routeIs('vendedor.pos.*') ? 'active' : ''); ?>">
                <div class="sb-link-left">
                    <i class="fas fa-cash-register"></i>
                    <span>Punto de Venta</span>
                </div>
            </a>
            <a href="<?php echo e(route('vendedor.ventas.index')); ?>"
               class="sb-link <?php echo e(request()->routeIs('vendedor.ventas.*') ? 'active' : ''); ?>">
                <div class="sb-link-left">
                    <i class="fas fa-receipt"></i>
                    <span>Mis Ventas</span>
                </div>
            </a>

            <div class="sb-group-title">Información</div>
            <a href="<?php echo e(route('vendedor.productos.index')); ?>"
               class="sb-link <?php echo e(request()->routeIs('vendedor.productos.*') ? 'active' : ''); ?>">
                <div class="sb-link-left">
                    <i class="fas fa-boxes"></i>
                    <span>Consultar Productos</span>
                </div>
            </a>

        </nav>

        <!-- Logout -->
        <div class="sb-footer">
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="sb-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar sesión</span>
                </button>
            </form>
        </div>

    </aside>

    <!-- ============ MAIN ============ -->
    <div class="main">

        <!-- Topbar -->
        <header class="topbar">
            <div>
                <h1 class="topbar-title"><?php echo $__env->yieldContent('page-title', 'Panel Vendedor'); ?></h1>
                <p class="topbar-sub"><?php echo $__env->yieldContent('page-subtitle', 'Bienvenido, ' . auth()->user()->name); ?></p>
            </div>
            <div class="topbar-badge">
                <i class="fas fa-user-tag"></i>
                <span>Vendedor</span>
            </div>
        </header>

        <!-- Content -->
        <main class="content">
            <?php echo $__env->yieldContent('content'); ?>
        </main>

    </div>

</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
    }
</script>

<?php echo $__env->yieldContent('scripts'); ?>

</body>
</html><?php /**PATH C:\xampp\htdocs\tienda\resources\views/layouts/vendedor.blade.php ENDPATH**/ ?>