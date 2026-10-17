<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MovimientoTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        DB::table('productos')->where('codigo_interno', 'LIKE', 'MOV-TEST%')->delete();
        DB::table('categorias')->where('nombre', 'MOV-CAT-TEST')->delete();
        User::where('email', 'admin.mov@test.com')->delete();

        $catId = DB::table('categorias')->insertGetId([
            'nombre' => 'MOV-CAT-TEST',
            'descripcion' => 'Test',
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('productos')->insert([
            'nombre' => 'MOV Producto',
            'idCategoria' => $catId,
            'precio' => 10.00,
            'stock_actual' => 50,
            'stock_minimo' => 5,
            'codigo_interno' => 'MOV-TEST-001',
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $admin = User::create([
            'name' => 'Admin Mov',
            'email' => 'admin.mov@test.com',
            'password' => bcrypt('password'),
            'activo' => 1,
        ]);
        DB::table('roles_usuarios')->insert(['idUsuario' => $admin->id, 'idRol' => 1, 'created_at' => now(), 'updated_at' => now()]);
    }

    /** CP-MOV-001 */
    public function test_listar_movimientos()
    {
        $admin = User::where('email', 'admin.mov@test.com')->first();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/movimientos')
                    ->pause(2000)
                    ->assertSee('Movimientos de Inventario');
        });
    }

    /** CP-MOV-002 */
    public function test_registrar_entrada()
    {
        $admin = User::where('email', 'admin.mov@test.com')->first();
        $idProducto = DB::table('productos')->where('codigo_interno', 'MOV-TEST-001')->value('idProducto');

        $this->browse(function (Browser $browser) use ($admin, $idProducto) {
            $browser->loginAs($admin)
                    ->visit('/admin/movimientos/create')
                    ->pause(2000)
                    ->select('idProducto', $idProducto)
                    ->radio('tipo', 'Entrada')
                    ->type('cantidad', '20')
                    ->type('referencia', 'Compra Dusk #001')
                    ->press('Registrar Movimiento')
                    ->pause(2000)
                    ->assertPathIs('/admin/movimientos');
        });

        // Verificar stock incrementado
        $stock = DB::table('productos')->where('idProducto', $idProducto)->value('stock_actual');
        $this->assertEquals(70, $stock);
    }

    /** CP-MOV-003 */
    public function test_registrar_salida()
    {
        $admin = User::where('email', 'admin.mov@test.com')->first();
        $idProducto = DB::table('productos')->where('codigo_interno', 'MOV-TEST-001')->value('idProducto');

        $this->browse(function (Browser $browser) use ($admin, $idProducto) {
            $browser->loginAs($admin)
                    ->visit('/admin/movimientos/create')
                    ->pause(2000)
                    ->select('idProducto', $idProducto)
                    ->radio('tipo', 'Salida')
                    ->type('cantidad', '10')
                    ->type('referencia', 'Salida Dusk #001')
                    ->press('Registrar Movimiento')
                    ->pause(2000)
                    ->assertPathIs('/admin/movimientos');
        });

        $stock = DB::table('productos')->where('idProducto', $idProducto)->value('stock_actual');
        $this->assertEquals(40, $stock);
    }

    /** CP-MOV-004 */
    public function test_ver_detalle_movimiento()
    {
        $admin = User::where('email', 'admin.mov@test.com')->first();
        $idProducto = DB::table('productos')->where('codigo_interno', 'MOV-TEST-001')->value('idProducto');

        $idMov = DB::table('movimientos_inventario')->insertGetId([
            'idProducto' => $idProducto,
            'idUsuario' => $admin->id,
            'tipo' => 'Entrada',
            'cantidad' => 5,
            'stock_anterior' => 50,
            'stock_nuevo' => 55,
            'referencia' => 'Test',
            'anulado' => 0,
            'created_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($admin, $idMov) {
            $browser->loginAs($admin)
                    ->visit("/admin/movimientos/{$idMov}")
                    ->pause(2000)
                    ->assertSee('Detalle de Movimiento')
                    ->assertSee('Entrada');
        });
    }
}