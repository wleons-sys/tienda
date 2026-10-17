<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProductoTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Limpiar
        DB::table('productos')->where('codigo_interno', 'LIKE', 'PROD-TEST%')->delete();
        DB::table('categorias')->where('nombre', 'CAT-TEST')->delete();
        User::where('email', 'admin.prod@test.com')->delete();

        // Crear categoría
        DB::table('categorias')->insert([
            'nombre' => 'CAT-TEST',
            'descripcion' => 'Test',
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear admin
        $admin = User::create([
            'name' => 'Admin Prod',
            'email' => 'admin.prod@test.com',
            'password' => bcrypt('password'),
            'activo' => 1,
        ]);
        DB::table('roles_usuarios')->insert(['idUsuario' => $admin->id, 'idRol' => 1, 'created_at' => now(), 'updated_at' => now()]);
    }

    /** CP-PROD-001 */
    public function test_listar_productos()
    {
        $admin = User::where('email', 'admin.prod@test.com')->first();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/productos')
                    ->pause(2000)
                    ->assertSee('Gestión de Productos')
                    ->assertPathIs('/admin/productos');
        });
    }

    /** CP-PROD-002 */
    public function test_crear_producto_exitoso()
    {
        $admin = User::where('email', 'admin.prod@test.com')->first();
        $catId = DB::table('categorias')->where('nombre', 'CAT-TEST')->value('idCategoria');

        $this->browse(function (Browser $browser) use ($admin, $catId) {
            $browser->loginAs($admin)
                    ->visit('/admin/productos/create')
                    ->pause(2000)
                    ->type('nombre', 'Producto de Prueba Dusk')
                    ->select('idCategoria', $catId)
                    ->type('precio', '25.50')
                    ->type('codigo_interno', 'PROD-TEST-001')
                    ->type('stock_actual', '100')
                    ->type('stock_minimo', '10')
                    ->press('Guardar Producto')
                    ->pause(2000)
                    ->assertPathIs('/admin/productos')
                    ->assertSee('Producto de Prueba Dusk');
        });
    }

    /** CP-PROD-003 */
    public function test_editar_producto()
    {
        $admin = User::where('email', 'admin.prod@test.com')->first();
        $catId = DB::table('categorias')->where('nombre', 'CAT-TEST')->value('idCategoria');

        $producto = DB::table('productos')->insertGetId([
            'nombre' => 'Producto Original',
            'idCategoria' => $catId,
            'precio' => 10.00,
            'stock_actual' => 50,
            'stock_minimo' => 5,
            'codigo_interno' => 'PROD-TEST-002',
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($admin, $producto) {
            $browser->loginAs($admin)
                    ->visit("/admin/productos/{$producto}/edit")
                    ->pause(2000)
                    ->clear('nombre')
                    ->type('nombre', 'Producto Modificado')
                    ->press('Actualizar Producto')
                    ->pause(2000)
                    ->assertPathIs('/admin/productos')
                    ->assertSee('Producto Modificado');
        });
    }

    /** CP-PROD-004 */
    public function test_buscar_producto()
    {
        $admin = User::where('email', 'admin.prod@test.com')->first();
        $catId = DB::table('categorias')->where('nombre', 'CAT-TEST')->value('idCategoria');

        DB::table('productos')->insert([
            'nombre' => 'Arroz Premium',
            'idCategoria' => $catId,
            'precio' => 15.00,
            'stock_actual' => 100,
            'stock_minimo' => 10,
            'codigo_interno' => 'PROD-TEST-003',
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/productos')
                    ->pause(2000)
                    ->type('buscar', 'Arroz')
                    ->press('Filtrar')
                    ->pause(2000)
                    ->assertSee('Arroz Premium');
        });
    }

    /** CP-PROD-005 */
    public function test_eliminar_producto()
    {
        $admin = User::where('email', 'admin.prod@test.com')->first();
        $catId = DB::table('categorias')->where('nombre', 'CAT-TEST')->value('idCategoria');

        DB::table('productos')->insert([
            'nombre' => 'Producto para Eliminar',
            'idCategoria' => $catId,
            'precio' => 5.00,
            'stock_actual' => 10,
            'stock_minimo' => 2,
            'codigo_interno' => 'PROD-TEST-DEL',
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/productos')
                    ->pause(2000)
                    ->assertSee('Producto para Eliminar');
        });

        // Verificar en BD que se puede eliminar
        $this->assertDatabaseHas('productos', ['codigo_interno' => 'PROD-TEST-DEL']);
    }

    /** CP-PROD-006 */
    public function test_ver_detalle_producto()
    {
        $admin = User::where('email', 'admin.prod@test.com')->first();
        $catId = DB::table('categorias')->where('nombre', 'CAT-TEST')->value('idCategoria');

        $producto = DB::table('productos')->insertGetId([
            'nombre' => 'Producto Detalle',
            'idCategoria' => $catId,
            'precio' => 99.99,
            'stock_actual' => 25,
            'stock_minimo' => 5,
            'codigo_interno' => 'PROD-TEST-DETAIL',
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($admin, $producto) {
            $browser->loginAs($admin)
                    ->visit("/admin/productos/{$producto}")
                    ->pause(2000)
                    ->assertSee('Producto Detalle')
                    ->assertSee('99.99')
                    ->assertSee('25');
        });
    }
}