<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Multiservicios León</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #0f172a; }
        h1, h2, h3, h4 { font-family: 'Poppins', sans-serif; }

        .layout { display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar {
            width: 260px; background: #0f172a; color: #fff;
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; bottom: 0; z-index: 50;
        }
        .sb-logo { padding: 22px; display: flex; align-items: center; gap: 11px; border-bottom: 1px solid rgba(255,255,255,0.06); text-decoration: none; }
        .sb-logo-icon { width: 42px; height: 42px; background: linear-gradient(135deg, #2563eb, #0ea5e9); border-radius: 11px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 18px; box-shadow: 0 6px 16px rgba(37,99,235,0.4); }
        .sb-logo-text { display: flex; flex-direction: column; line-height: 1.1; }
        .sb-logo-text span:first-child { font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600; color: #fff; }
        .sb-logo-text span:last-child { font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 700; color: #fbbf24; margin-top: 2px; }

        .sb-user-card { padding: 20px 22px; border-bottom: 1px solid rgba(255,255,255,0.06); display: flex; align-items: center; gap: 12px; }
        .sb-avatar { width: 44px; height: 44px; background: linear-gradient(135deg, #2563eb, #0ea5e9); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 15px; flex-shrink: 0; }
        .sb-user-info { flex: 1; min-width: 0; }
        .sb-user-name { font-size: 13px; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sb-user-role { font-size: 11px; color: #38bdf8; font-weight: 500; margin-top: 2px; }

        .sb-menu { flex: 1; padding: 18px 14px; overflow-y: auto; }
        .sb-group-title { font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; padding: 12px 14px 6px; }
        .sb-link { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 11px 14px; border-radius: 10px; color: #cbd5e1; font-size: 14px; font-weight: 500; text-decoration: none; transition: all 0.25s ease; margin-bottom: 2px; position: relative; }
        .sb-link-left { display: flex; align-items: center; gap: 12px; }
        .sb-link i { width: 20px; font-size: 15px; text-align: center; color: #64748b; transition: color 0.25s ease; }
        .sb-link:hover { background: rgba(255,255,255,0.05); color: #fff; }
        .sb-link:hover i { color: #0ea5e9; }
        .sb-link.active { background: linear-gradient(135deg, rgba(37,99,235,0.2), rgba(14,165,233,0.1)); color: #fff; font-weight: 600; }
        .sb-link.active i { color: #38bdf8; }
        .sb-link.active::before { content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%); width: 3px; height: 20px; background: #0ea5e9; border-radius: 0 3px 3px 0; }
        .sb-badge { background: #0ea5e9; color: #fff; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 100px; min-width: 22px; text-align: center; }

        .sb-footer { padding: 16px; border-top: 1px solid rgba(255,255,255,0.06); }
        .sb-logout { display: flex; align-items: center; gap: 10px; padding: 11px 14px; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #fca5a5; font-size: 13px; font-weight: 600; border-radius: 10px; cursor: pointer; transition: all 0.25s ease; width: 100%; justify-content: center; }
        .sb-logout:hover { background: #dc2626; border-color: #dc2626; color: #fff; }

        /* Main */
        .main { flex: 1; margin-left: 260px; min-width: 0; }
        .topbar { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 20px 32px; display: flex; align-items: center; justify-content: space-between; gap: 20px; position: sticky; top: 0; z-index: 40; }
        .topbar-title { font-family: 'Poppins', sans-serif; font-size: 22px; font-weight: 700; color: #0f172a; letter-spacing: -0.5px; }
        .topbar-sub { font-size: 13px; color: #64748b; margin-top: 3px; }
        .content { padding: 28px 32px; }

        /* Card */
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; margin-bottom: 20px; }
        .card-head { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }

        /* Filters */
        .filters { display: flex; gap: 10px; flex: 1; flex-wrap: wrap; }
        .filters input, .filters select { padding: 9px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 13px; outline: none; transition: all 0.25s ease; font-family: 'Inter', sans-serif; }
        .filters input { min-width: 240px; }
        .filters input:focus, .filters select:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all 0.25s ease; }
        .btn-primary { background: linear-gradient(135deg, #2563eb, #0ea5e9); color: #fff; box-shadow: 0 6px 16px rgba(37,99,235,0.3); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(37,99,235,0.45); }
        .btn-ghost { background: #f1f5f9; color: #475569; }
        .btn-ghost:hover { background: #e2e8f0; }

        .btn-icon { width: 34px; height: 34px; background: #f1f5f9; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; color: #475569; text-decoration: none; transition: all 0.25s ease; font-size: 13px; border: none; cursor: pointer; }
        .btn-icon:hover { background: #2563eb; color: #fff; }
        .btn-icon-danger:hover { background: #dc2626; }

        /* Table */
        .table { width: 100%; border-collapse: collapse; }
        .table thead { background: #f8fafc; }
        .table th { padding: 14px 24px; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .table td { padding: 16px 24px; font-size: 13px; color: #334155; border-bottom: 1px solid #f1f5f9; }
        .table tbody tr:hover { background: #f8fafc; }
        .table tbody tr:last-child td { border-bottom: none; }
        .table strong { font-weight: 600; color: #0f172a; }
        .actions { display: flex; gap: 6px; }
        .empty { text-align: center; color: #94a3b8; padding: 40px !important; }

        /* Badges */
        .badge { display: inline-block; padding: 4px 10px; font-size: 11px; font-weight: 700; border-radius: 100px; }
        .badge-green { background: #ecfdf5; color: #059669; }
        .badge-blue { background: #eff6ff; color: #1d4ed8; }
        .badge-red { background: #fef2f2; color: #dc2626; }
        .badge-amber { background: #fef3c7; color: #d97706; }
        .badge-cyan { background: #ecfeff; color: #0891b2; }

        /* Alerts */
        .alert { padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 500; }
        .alert-success { background: #ecfdf5; border-left: 4px solid #10b981; color: #065f46; }
        .alert-error { background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; }
        .alert ul { margin: 0; padding-left: 20px; }

        /* Form */
        .form { padding: 28px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px; }
        .form-group label { font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600; color: #1e3a8a; }
        .form-group input, .form-group select, .form-group textarea { padding: 12px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 14px; outline: none; transition: all 0.25s ease; font-family: 'Inter', sans-serif; background: #fff; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: #2563eb; box-shadow: 0 0 0 4px rgba(37,99,235,0.1); }
        .form-group small { font-size: 11px; color: #94a3b8; }
        .checkbox { display: flex; align-items: center; gap: 8px; font-size: 13px; cursor: pointer; }
        .checkbox input { width: auto; }
        .form-actions { display: flex; justify-content: flex-end; gap: 12px; padding-top: 20px; border-top: 1px solid #f1f5f9; margin-top: 20px; }

        /* Detail */
        .detail-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; padding: 28px; }
        .detail-item { display: flex; flex-direction: column; gap: 6px; }
        .detail-label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; }
        .detail-value { font-size: 15px; font-weight: 600; color: #0f172a; }

        /* Pagination */
        .pagination { padding: 20px 24px; border-top: 1px solid #f1f5f9; }
        .pagination nav { display: flex; justify-content: center; }

        /* Responsive */
        @media (max-width: 900px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .form-row { grid-template-columns: 1fr; }
            .detail-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 640px) {
            .content { padding: 20px; }
            .topbar { padding: 16px 20px; }
            .detail-grid { grid-template-columns: 1fr; }
            .table { font-size: 12px; }
            .table th, .table td { padding: 12px 14px; }
        }
    </style>
</head>
<body>

<div class="layout">

    <aside class="sidebar">
        <a href="/" class="sb-logo">
            <div class="sb-logo-icon"><i class="fas fa-cubes"></i></div>
            <div class="sb-logo-text">
                <span>Multiservicios</span>
                <span>León</span>
            </div>
        </a>

        <div class="sb-user-card">
            <div class="sb-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div class="sb-user-info">
                <div class="sb-user-name">{{ auth()->user()->name }}</div>
                <div class="sb-user-role">Administrador</div>
            </div>
        </div>

        <nav class="sb-menu">
            <div class="sb-group-title">Principal</div>
            <a href="{{ route('admin.dashboard') }}" class="sb-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <div class="sb-link-left"><i class="fas fa-home"></i><span>Dashboard</span></div>
            </a>

            <div class="sb-group-title">Gestión</div>
            <a href="{{ route('admin.usuarios.index') }}" class="sb-link {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
                <div class="sb-link-left"><i class="fas fa-users"></i><span>Usuarios</span></div>
            </a>
            <a href="{{ route('admin.categorias.index') }}" class="sb-link {{ request()->routeIs('admin.categorias.*') ? 'active' : '' }}">
                <div class="sb-link-left"><i class="fas fa-tags"></i><span>Categorías</span></div>
            </a>
            <a href="{{ route('admin.productos.index') }}" class="sb-link {{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
                <div class="sb-link-left"><i class="fas fa-boxes"></i><span>Productos</span></div>
            </a>
            <a href="{{ route('admin.ventas.index') }}" class="sb-link {{ request()->routeIs('admin.ventas.*') ? 'active' : '' }}">
                <div class="sb-link-left"><i class="fas fa-cash-register"></i><span>Ventas</span></div>
            </a>

            <div class="sb-group-title">Sistema</div>
            <a href="{{ route('admin.movimientos.index') }}" class="sb-link {{ request()->routeIs('admin.movimientos.*') ? 'active' : '' }}">
    <div class="sb-link-left"><i class="fas fa-exchange-alt"></i><span>Movimientos</span></div>
</a>
<a href="{{ route('admin.reportes.index') }}" class="sb-link {{ request()->routeIs('admin.reportes.*') ? 'active' : '' }}">
    <div class="sb-link-left"><i class="fas fa-chart-bar"></i><span>Reportes</span></div>
</a>
        </nav>

        <div class="sb-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sb-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar sesión</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="main">
        <header class="topbar">
            <div>
                <h1 class="topbar-title">@yield('page-title', 'Panel')</h1>
                <p class="topbar-sub">@yield('page-subtitle', '')</p>
            </div>
        </header>

        <main class="content">
            @yield('content')
        </main>
    </div>

</div>

</body>
</html>