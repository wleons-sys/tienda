<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multiservicios León — Sistema de Gestión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            color: #0f172a;
        }

        h1, h2, h3 { font-family: 'Poppins', sans-serif; }

        /* ============ HERO ============ */
        .hero {
            position: relative;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #0ea5e9 100%);
            color: #ffffff;
            padding: 80px 32px 140px;
            text-align: center;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 520px;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 30px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 8px;
            background: 
                radial-gradient(circle, rgba(255,255,255,0.4) 2px, transparent 2px),
                radial-gradient(circle, rgba(255,255,255,0.2) 2px, transparent 2px);
            background-size: 20px 8px, 20px 8px;
            background-position: 0 0, 10px 0;
            background-repeat: repeat-x;
        }

        .hero-wave {
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            line-height: 0;
            z-index: 2;
        }

        .hero-wave svg {
            width: 100%;
            height: 90px;
            display: block;
        }

        .hero-inner {
            position: relative;
            z-index: 3;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px 0;
        }

        /* Badge superior */
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 16px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 100px;
            font-size: 10px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 28px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .hero-badge .bars {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .hero-badge .bars span {
            width: 10px;
            height: 2px;
            background: #ffffff;
            border-radius: 2px;
        }

        .hero-badge .bars span:nth-child(2) { width: 7px; }
        .hero-badge .bars span:nth-child(3) { width: 9px; }

        /* Título */
        .hero-title {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(32px, 4.5vw, 64px);
            font-weight: 900;
            line-height: 1;
            letter-spacing: -2px;
            margin-bottom: 16px;
            color: #ffffff;
        }

        .hero-title .gold {
            color: #fbbf24;
        }

        .hero-subtitle {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(13px, 1.4vw, 16px);
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 32px;
            letter-spacing: 0.3px;
        }

        /* Pills */
        .hero-pills {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 36px;
        }

        .hero-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 100px;
            font-size: 12px;
            font-weight: 600;
            color: #ffffff;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .hero-pill i {
            color: #fbbf24;
            font-size: 11px;
        }

        /* Botón Ingresar */
        .hero-cta {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 36px;
            background: #ffffff;
            color: #1e3a8a;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            font-weight: 700;
            border: none;
            border-radius: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.18);
            letter-spacing: 0.3px;
        }

        .hero-cta:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.28);
        }

        .hero-cta i {
            transition: transform 0.3s ease;
            font-size: 13px;
        }

        .hero-cta:hover i {
            transform: translateX(4px);
        }

        /* ============ CATEGORÍAS ============ */
        .section {
            padding: 60px 32px 100px;
            background: #ffffff;
        }

        .section-inner {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-head {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 44px;
        }

        .section-title-wrap {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-shrink: 0;
        }

        .section-title {
            font-family: 'Poppins', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.3px;
        }

        .section-line {
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .section-count {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: #0f172a;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-radius: 100px;
            flex-shrink: 0;
        }

        .cat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .cat {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            border: 1.5px solid;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
            text-decoration: none;
        }

        .cat:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 60px -20px var(--shadow);
            border-color: transparent;
        }

        .cat.verde { --bg-light: #ecfdf5; --bg-mid: #d1fae5; --color: #059669; --shadow: rgba(16,185,129,0.25); border-color: #a7f3d0; }
        .cat.amarillo { --bg-light: #fffbeb; --bg-mid: #fef3c7; --color: #d97706; --shadow: rgba(245,158,11,0.25); border-color: #fcd34d; }
        .cat.azul { --bg-light: #ecfeff; --bg-mid: #cffafe; --color: #0891b2; --shadow: rgba(6,182,212,0.25); border-color: #67e8f9; }

        .cat-body {
            padding: 32px 28px 24px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .cat-icon {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: var(--color);
            background: linear-gradient(135deg, var(--bg-light), var(--bg-mid));
            margin-bottom: 22px;
            box-shadow: 0 8px 20px -8px var(--shadow);
        }

        .cat-name {
            font-family: 'Poppins', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: var(--color);
            margin-bottom: 12px;
            letter-spacing: -0.6px;
            line-height: 1.1;
        }

        .cat-desc {
            font-size: 14px;
            line-height: 1.65;
            color: #475569;
            flex: 1;
        }

        .cat-footer {
            padding: 18px 28px;
            background: var(--color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .cat-footer-text {
            display: flex;
            flex-direction: column;
            color: #ffffff;
        }

        .cat-footer-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            opacity: 0.8;
            margin-bottom: 2px;
        }

        .cat-footer-action {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .cat-footer i {
            color: #ffffff;
            font-size: 16px;
            transition: transform 0.3s ease;
        }

        .cat:hover .cat-footer i {
            transform: translateX(6px);
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 900px) {
            .cat-grid { grid-template-columns: 1fr; max-width: 480px; margin: 0 auto; }
            .hero { padding: 60px 20px 120px; }
            .section { padding: 40px 20px 80px; }
            .hero-wave svg { height: 70px; }
        }

        @media (max-width: 520px) {
            .hero { padding: 50px 16px 100px; }
            .hero-title { letter-spacing: -1.5px; }
            .hero-subtitle { margin-bottom: 24px; }
            .hero-pills { margin-bottom: 28px; }
            .hero-cta { padding: 14px 28px; font-size: 14px; }
            .section-title { font-size: 18px; }
            .section-count { display: none; }
            .cat-body { padding: 24px 22px 20px; }
            .cat-footer { padding: 16px 22px; }
        }
    </style>
</head>
<body>

    <!-- ============================================
         HERO
    ============================================ -->
    <section class="hero">

        <div class="hero-inner">

            <!-- Badge superior -->
            <div class="hero-badge">
                <div class="bars">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <span>Multiservicios · Guatemala · Sistema de Gestión</span>
            </div>

            <!-- Título principal -->
            <h1 class="hero-title">
                MULTISERVICIOS <span class="gold">LEÓN</span>
            </h1>

            <!-- Subtítulo -->
            <p class="hero-subtitle">
                Sistema de Control de Inventario y Ventas
            </p>

            <!-- Pills informativos -->
            <div class="hero-pills">
                <span class="hero-pill">
                    <i class="fas fa-map-marker-alt"></i>
                    Guatemala
                </span>
                <span class="hero-pill">
                    <i class="fas fa-boxes"></i>
                    Inventario
                </span>
                <span class="hero-pill">
                    <i class="fas fa-cash-register"></i>
                    Ventas
                </span>
            </div>

            <!-- Único botón -->
            <button onclick="openLoginModal()" class="hero-cta">
                <i class="fas fa-sign-in-alt"></i>
                <span>Ingresar al Sistema</span>
                <i class="fas fa-arrow-right"></i>
            </button>

        </div>

        <!-- Wave decorativa -->
        <div class="hero-wave">
            <svg viewBox="0 0 1440 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,60 C320,120 480,0 720,40 C960,80 1120,20 1440,60 L1440,120 L0,120 Z" fill="#ffffff"/>
            </svg>
        </div>

    </section>

    <!-- ============================================
         SECCIÓN CATEGORÍAS
    ============================================ -->
    <section class="section">
        <div class="section-inner">

            <div class="section-head">
                <div class="section-title-wrap">
                    <h2 class="section-title">Categorías de Productos</h2>
                </div>
                <div class="section-line"></div>
                <div class="section-count">
                    <i class="fas fa-tags"></i>
                    3 categorías
                </div>
            </div>

            <div class="cat-grid">

                <!-- Consumo Diario -->
                <a href="#" class="cat verde">
                    <div class="cat-body">
                        <div class="cat-icon">
                            <i class="fas fa-shopping-basket"></i>
                        </div>
                        <h3 class="cat-name">Consumo Diario</h3>
                        <p class="cat-desc">
                            Alimentos, bebidas, snacks y productos de primera 
                            necesidad para el hogar.
                        </p>
                    </div>
                    <div class="cat-footer">
                        <div class="cat-footer-text">
                            <span class="cat-footer-label">Ver productos</span>
                            <span class="cat-footer-action">GESTIONAR</span>
                        </div>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </a>

                <!-- Librería -->
                <a href="#" class="cat amarillo">
                    <div class="cat-body">
                        <div class="cat-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3 class="cat-name">Librería</h3>
                        <p class="cat-desc">
                            Papelería, útiles escolares, material de oficina 
                            y productos educativos.
                        </p>
                    </div>
                    <div class="cat-footer">
                        <div class="cat-footer-text">
                            <span class="cat-footer-label">Ver productos</span>
                            <span class="cat-footer-action">GESTIONAR</span>
                        </div>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </a>

                <!-- Farmacia -->
                <a href="#" class="cat azul">
                    <div class="cat-body">
                        <div class="cat-icon">
                            <i class="fas fa-pills"></i>
                        </div>
                        <h3 class="cat-name">Farmacia</h3>
                        <p class="cat-desc">
                            Medicamentos, vitaminas, productos de cuidado 
                            personal y primeros auxilios.
                        </p>
                    </div>
                    <div class="cat-footer">
                        <div class="cat-footer-text">
                            <span class="cat-footer-label">Ver productos</span>
                            <span class="cat-footer-action">GESTIONAR</span>
                        </div>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </a>

            </div>

        </div>
    </section>

    <!-- FOOTER -->
    <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- MODAL LOGIN -->
    <?php echo $__env->make('components.login-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</body>
</html><?php /**PATH C:\xampp\htdocs\tienda\resources\views/portal/index.blade.php ENDPATH**/ ?>