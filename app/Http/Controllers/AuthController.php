<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return redirect()->route('portal');
    }

    /**
     * Procesar el login
     */
    public function login(Request $request)
    {
        // 1. Validar
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'El correo es obligatorio',
            'email.email' => 'Ingresa un correo válido',
            'password.required' => 'La contraseña es obligatoria',
        ]);

        // 2. Buscar usuario en la tabla `users`
        $user = User::where('email', $request->email)->first();

        // 3. Verificar que exista
        if (!$user) {
            return back()->withErrors([
                'email' => 'Las credenciales no coinciden con nuestros registros.',
            ])->withInput($request->only('email'));
        }

        // 4. Intentar autenticar
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ], $request->boolean('remember'))) {

            // 5. Regenerar sesión
            $request->session()->regenerate();

            // 6. Registrar auditoría
            try {
                DB::table('logs_auditoria')->insert([
                    'idUsuario' => $user->id,
                    'accion' => 'Login',
                    'tabla_afectada' => 'users',
                    'registro_afectado_id' => $user->id,
                    'descripcion' => 'Inicio de sesión exitoso',
                    'ip_usuario' => $request->ip(),
                    'created_at' => now(),
                ]);
            } catch (\Exception $e) {
                // Ignorar errores de auditoría
            }

            // 7. Redirigir según rol
            return $this->redirectByRole($user);
        }

        // 8. Credenciales incorrectas
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->withInput($request->only('email'));
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal');
    }

    /**
     * Redirigir según el rol
     */
    protected function redirectByRole($user)
    {
        if ($user->tieneRol('Administrador')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->tieneRol('Vendedor')) {
            return redirect()->route('vendedor.dashboard');
        }

        Auth::logout();
        return redirect()->route('portal')->withErrors([
            'email' => 'Tu usuario no tiene un rol asignado.',
        ]);
    }
}