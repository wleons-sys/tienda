<header class="hd">
    <div class="hd-wrap">
        <a href="/" class="hd-logo">
            <div class="hd-icon"><i class="fas fa-cubes"></i></div>
            <span class="hd-name">Multiservicios <em>León</em></span>
        </a>
    </div>
</header>

<style>
    .hd {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 50;
        padding: 20px 0;
    }

    .hd-wrap {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 32px;
        display: flex;
        align-items: center;
    }

    .hd-logo {
        display: flex;
        align-items: center;
        gap: 11px;
        text-decoration: none;
    }

    .hd-icon {
        width: 42px;
        height: 42px;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 18px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .hd-name {
        font-family: 'Poppins', sans-serif;
        font-size: 16px;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: -0.3px;
    }

    .hd-name em {
        color: #fbbf24;
        font-style: normal;
    }

    @media (max-width: 640px) {
        .hd-wrap { padding: 0 20px; }
        .hd-name { font-size: 14px; }
    }
</style>