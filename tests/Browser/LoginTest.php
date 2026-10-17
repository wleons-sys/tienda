<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /**
     * CP-LOGIN-001: Inicio de sesión exitoso
     */
    public function test_login_con_credenciales_correctas()
    {
        // Limpiar usuario de prueba si existe
        User::where('email', 'test@multiserviciosleon.com')->delete();

        $user = User::create([
            'name' => 'Administrador Test',
            'email' => 'test@multiserviciosleon.com',
            'password' => bcrypt('Admin123'),
            'activo' => 1,
        ]);

        \DB::table('roles_usuarios')->insert([
            'idUsuario' => $user->id,
            'idRol' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->pause(1500)
                    ->click('.btn-main')          // ✅ CAMBIO: .btn-main
                    ->pause(1000)
                    ->type('email', 'test@multiserviciosleon.com')
                    ->type('password', 'Admin123')
                    ->click('button[type="submit"]')
                    ->waitForLocation('/admin/dashboard', 15)
                    ->assertPathIs('/admin/dashboard')
                    ->assertSee('Administrador Test');
        });

        // Limpieza
        \DB::table('roles_usuarios')->where('idUsuario', $user->id)->delete();
        $user->delete();
    }

    /**
     * CP-LOGIN-002: Inicio de sesión fallido
     */
    public function test_login_con_credenciales_incorrectas()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->pause(1500)
                    ->click('.btn-main')          // ✅ CAMBIO: .btn-main
                    ->pause(1000)
                    ->type('email', 'admin@gmail.com')
                    ->type('password', 'wrongpassword')
                    ->click('button[type="submit"]')
                    ->pause(2000)
                    ->assertSee('Las credenciales no coinciden');
        });
    }
}