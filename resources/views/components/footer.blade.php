<footer class="ft">
    <div class="ft-wrap">

        <!-- Logo/Brand -->
        <h3 class="ft-brand">
            Multiservicios <span>León</span>
        </h3>

        <!-- Tags -->
        <div class="ft-tags">
            <span class="ft-tag tag-blue">INVENTARIO</span>
            <span class="ft-tag tag-green">VENTAS</span>
            <span class="ft-tag tag-gold">REPORTES</span>
        </div>

        <!-- Descripción -->
        <p class="ft-desc">
            Sistema de Gestión de Inventario y Ventas · Multiservicios León
        </p>

        <!-- Info -->
        <p class="ft-info">
            Guatemala, Guatemala · © {{ date('Y') }}
        </p>

        <!-- Créditos -->
        <p class="ft-credits">
            Desarrollado con Laravel · Sistema de código abierto
        </p>

    </div>
</footer>

<style>
    .ft {
        background: #0f172a;
        padding: 60px 32px 50px;
        text-align: center;
    }

    .ft-wrap {
        max-width: 800px;
        margin: 0 auto;
    }

    .ft-brand {
        font-family: 'Poppins', sans-serif;
        font-size: 26px;
        font-weight: 700;
        font-style: italic;
        color: #ffffff;
        letter-spacing: -0.5px;
        margin-bottom: 24px;
    }

    .ft-brand span {
        background: linear-gradient(135deg, #0ea5e9, #06b6d4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .ft-tags {
        display: flex;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .ft-tag {
        display: inline-block;
        padding: 7px 18px;
        font-family: 'Poppins', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
        border-radius: 100px;
    }

    .tag-blue {
        background: rgba(14, 165, 233, 0.15);
        color: #38bdf8;
        border: 1px solid rgba(14, 165, 233, 0.3);
    }

    .tag-green {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .tag-gold {
        background: rgba(251, 191, 36, 0.15);
        color: #fbbf24;
        border: 1px solid rgba(251, 191, 36, 0.3);
    }

    .ft-desc {
        font-size: 14px;
        color: #cbd5e1;
        margin-bottom: 10px;
        line-height: 1.6;
    }

    .ft-info {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 6px;
    }

    .ft-credits {
        font-size: 12px;
        color: #475569;
        margin-top: 12px;
        font-style: italic;
    }

    @media (max-width: 520px) {
        .ft { padding: 40px 20px; }
        .ft-brand { font-size: 22px; }
        .ft-tags { gap: 8px; }
        .ft-tag { padding: 6px 14px; font-size: 10px; }
    }
</style>