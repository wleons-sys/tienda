<!-- ============================================
     MODAL DE LOGIN
============================================ -->
<div id="loginModal" class="lm-overlay">
    <div class="lm-card">

        <!-- Botón cerrar -->
        <button onclick="closeLoginModal()" class="lm-close" aria-label="Cerrar">
            <i class="fas fa-times"></i>
        </button>

        <!-- ============ LOGO / ÍCONO ============ -->
        <div class="lm-logo">
            <div class="lm-logo-icon">
                <i class="fas fa-cubes"></i>
            </div>
            <h1 class="lm-brand">Multiservicios <span>León</span></h1>
            <p class="lm-welcome">¡Bienvenido de nuevo!</p>
        </div>

        <!-- ============ MENSAJES DE ERROR ============ -->
        @if ($errors->any())
            <div class="lm-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- ============ FORMULARIO ============ -->
        <form method="POST" action="{{ route('login') }}" class="lm-form">
            @csrf

            <!-- Usuario -->
            <div class="lm-field">
                <label class="lm-label" for="email">
                    <i class="fas fa-user"></i>
                    Usuario
                </label>
                <div class="lm-input-wrap">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Tu correo electrónico"
                        required
                        autocomplete="email"
                    >
                </div>
            </div>

            <!-- Contraseña -->
            <div class="lm-field">
                <label class="lm-label" for="password">
                    <i class="fas fa-key"></i>
                    Contraseña
                </label>
                <div class="lm-input-wrap">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                    >
                    <button type="button" onclick="togglePassword()" class="lm-eye" aria-label="Mostrar contraseña">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Botón Acceder -->
            <button type="submit" class="lm-submit">
                <i class="fas fa-rocket"></i>
                <span>¡Acceder!</span>
            </button>

            <!-- Links inferiores -->
            <div class="lm-links">
                <a href="#" onclick="closeLoginModal(); return false;" class="lm-link-back">
                    <i class="fas fa-arrow-left"></i>
                    Regresar
                </a>
                <span class="lm-divider">|</span>
                <a href="#" class="lm-link-forgot">
                    <i class="fas fa-lock"></i>
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

        </form>

    </div>
</div>

<style>
    /* ============ OVERLAY ============ */
    .lm-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.35s ease, visibility 0.35s ease;
    }

    .lm-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .lm-overlay.hidden {
        display: none;
    }

    /* ============ CARD ============ */
    .lm-card {
        position: relative;
        width: 100%;
        max-width: 440px;
        background: #ffffff;
        border-radius: 20px;
        padding: 44px 40px 36px;
        box-shadow:
            0 30px 60px -20px rgba(15, 23, 42, 0.25),
            0 0 0 1px rgba(15, 23, 42, 0.04);
        transform: scale(0.94) translateY(20px);
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        max-height: 95vh;
        overflow-y: auto;
    }

    .lm-overlay.active .lm-card {
        transform: scale(1) translateY(0);
    }

    /* Botón cerrar */
    .lm-close {
        position: absolute;
        top: 16px;
        right: 16px;
        width: 36px;
        height: 36px;
        background: #f1f5f9;
        border: none;
        border-radius: 10px;
        color: #64748b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: all 0.25s ease;
    }

    .lm-close:hover {
        background: #ef4444;
        color: #ffffff;
        transform: rotate(90deg);
    }

    /* ============ LOGO ============ */
    .lm-logo {
        text-align: center;
        margin-bottom: 30px;
    }

    .lm-logo-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 16px;
        background: linear-gradient(135deg, #1e3a8a, #2563eb);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 30px;
        box-shadow: 0 12px 28px rgba(37, 99, 235, 0.35);
    }

    .lm-brand {
        font-family: 'Poppins', sans-serif;
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
        letter-spacing: -0.3px;
    }

    .lm-brand span {
        color: #f59e0b;
    }

    .lm-welcome {
        font-size: 14px;
        color: #64748b;
        font-weight: 400;
    }

    /* ============ ERROR ============ */
    .lm-error {
        display: flex;
        gap: 10px;
        padding: 12px 14px;
        background: #fef2f2;
        border-left: 3px solid #ef4444;
        border-radius: 10px;
        margin-bottom: 20px;
        color: #991b1b;
        font-size: 13px;
        animation: shake 0.4s ease;
    }

    .lm-error i {
        color: #ef4444;
        font-size: 15px;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .lm-error p {
        margin: 0;
        line-height: 1.5;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-6px); }
        75% { transform: translateX(6px); }
    }

    /* ============ FORM ============ */
    .lm-form {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .lm-field {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .lm-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: #1e3a8a;
    }

    .lm-label i {
        color: #2563eb;
        font-size: 13px;
    }

    /* Inputs */
    .lm-input-wrap {
        position: relative;
    }

    .lm-field input {
        width: 100%;
        padding: 14px 16px;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 11px;
        color: #0f172a;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        outline: none;
        transition: all 0.25s ease;
    }

    .lm-field input::placeholder {
        color: #94a3b8;
    }

    .lm-field input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    /* Password */
    .lm-input-wrap input {
        padding-right: 48px;
    }

    .lm-eye {
        position: absolute;
        right: 6px;
        top: 50%;
        transform: translateY(-50%);
        width: 36px;
        height: 36px;
        background: transparent;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .lm-eye:hover {
        background: #f1f5f9;
        color: #2563eb;
    }

    /* ============ BOTÓN SUBMIT ============ */
    .lm-submit {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 15px 24px;
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        color: #ffffff;
        border: none;
        border-radius: 11px;
        font-family: 'Poppins', sans-serif;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 10px 24px rgba(16, 185, 129, 0.35);
        letter-spacing: 0.3px;
        margin-top: 6px;
    }

    .lm-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 32px rgba(16, 185, 129, 0.5);
    }

    .lm-submit i {
        font-size: 14px;
    }

    /* ============ LINKS ============ */
    .lm-links {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 14px;
        margin-top: 8px;
        font-size: 13px;
        flex-wrap: wrap;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
    }

    .lm-link-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #2563eb;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .lm-link-back:hover {
        color: #1e40af;
    }

    .lm-divider {
        color: #cbd5e1;
        font-weight: 300;
    }

    .lm-link-forgot {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #d97706;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .lm-link-forgot:hover {
        color: #b45309;
    }

    .lm-link-back i,
    .lm-link-forgot i {
        font-size: 11px;
    }

    /* ============ RESPONSIVE ============ */
    @media (max-width: 480px) {
        .lm-overlay {
            padding: 12px;
        }

        .lm-card {
            padding: 36px 26px 28px;
            border-radius: 18px;
        }

        .lm-logo-icon {
            width: 62px;
            height: 62px;
            font-size: 26px;
        }

        .lm-brand {
            font-size: 19px;
        }

        .lm-links {
            gap: 10px;
            font-size: 12px;
        }
    }

    /* Scrollbar */
    .lm-card::-webkit-scrollbar { width: 4px; }
    .lm-card::-webkit-scrollbar-track { background: transparent; }
    .lm-card::-webkit-scrollbar-thumb { background: #e4e4e7; border-radius: 2px; }
</style>

<script>
    function openLoginModal() {
        const modal = document.getElementById('loginModal');
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.add('active'), 10);
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('email')?.focus(), 400);
    }

    function closeLoginModal() {
        const modal = document.getElementById('loginModal');
        modal.classList.remove('active');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 350);
    }

    document.getElementById('loginModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeLoginModal();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeLoginModal();
    });

    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', () => openLoginModal());
    </script>
@endif