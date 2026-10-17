<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CategoriaTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        DB::table('categorias')->where('nombre', 'LIKE', 'CAT-TEST%')->delete();
        User::where('email', 'admin.cat@test.com')->delete();

        $admin = User::create([
            'name' => 'Admin Cat',
            'email' => 'admin.cat@test.com',
            'password' => bcrypt('password'),
            'activo' => 1,
        ]);
        DB::table('roles_usuarios')->insert(['idUsuario' => $admin->id, 'idRol' => 1, 'created_at' => now(), 'updated_at' => now()]);
    }

    /** CP-CAT-001 */
    public function test_listar_categorias()
    {
        $admin = User::where('email', 'admin.cat@test.com')->first();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/categorias')
                    ->pause(2000)
                    ->assertSee('Gestión de Categorías')
                    ->assertPathIs('/admin/categorias');
        });
    }

    /** CP-CAT-002 */
    public function test_crear_categoria()
    {
        $admin = User::where('email', 'admin.cat@test.com')->first();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/categorias/create')
                    ->pause(2000)
                    ->type('nombre', 'CAT-TEST-NUEVA')
                    ->type('descripcion', 'Categoría creada desde Dusk')
                    ->press('Guardar Categoría')
                    ->pause(2000)
                    ->assertPathIs('/admin/categorias')
                    ->assertSee('CAT-TEST-NUEVA');
        });
    }

    /** CP-CAT-003 */
    public function test_editar_categoria()
    {
        $admin = User::where('email', 'admin.cat@test.com')->first();

        $id = DB::table('categorias')->insertGetId([
            'nombre' => 'CAT-TEST-EDIT',
            'descripcion' => 'Original',
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($admin, $id) {
            $browser->loginAs($admin)
                    ->visit("/admin/categorias/{$id}/edit")
                    ->pause(2000)
                    ->clear('nombre')
                    ->type('nombre', 'CAT-TEST-MODIFICADA')
                    ->press('Actualizar Categoría')
                    ->pause(2000)
                    ->assertSee('CAT-TEST-MODIFICADA');
        });
    }

    /** CP-CAT-004 */
    public function test_ver_detalle_categoria()
    {
        $admin = User::where('email', 'admin.cat@test.com')->first();

        $id = DB::table('categorias')->insertGetId([
            'nombre' => 'CAT-TEST-DETAIL',
            'descripcion' => 'Categoría de prueba',
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($admin, $id) {
            $browser->loginAs($admin)
                    ->visit("/admin/categorias/{$id}")
                    ->pause(2000)
                    ->assertSee('CAT-TEST-DETAIL')
                    ->assertSee('Categoría de prueba');
        });
    }
}