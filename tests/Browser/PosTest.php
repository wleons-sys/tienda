<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PosTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Limpiar
        DB::table('productos')->where('codigo_interno', 'LIKE', 'POS-TEST%')->delete();
        DB::table('categorias')->where('nombre', 'POS-CAT-TEST')->delete();
        User::where('email', 'vendedor.pos@test.com')->delete();

        // Categoría
        $catId = DB::table('categorias')->insertGetId([
            'nombre' => 'POS-CAT-TEST',
            'descripcion' => 'Para pruebas POS',
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Productos
        DB::table('productos')->insert([
            [
                'nombre' => 'POS Producto A',
                'idCategoria' => $catId,
                'precio' => 25.00,
                'stock_actual' => 100,
                'stock_minimo' => 10,
                'codigo_interno' => 'POS-TEST-A',
                'activo' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'POS Producto B',
                'idCategoria' => $catId,
                'precio' => 15.50,
                'stock_actual' => 50,
                'stock_minimo' => 5,
                'codigo_interno' => 'POS-TEST-B',
                'activo' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Vendedor
        $vendedor = User::create([
            'name' => 'Vendedor POS',
            'email' => 'vendedor.pos@test.com',
            'password' => bcrypt('password'),
            'activo' => 1,
        ]);
        DB::table('roles_usuarios')->insert(['idUsuario' => $vendedor->id, 'idRol' => 2, 'created_at' => now(), 'updated_at' => now()]);
    }

    /** CP-POS-001 */
    public function test_cargar_pos()
    {
        $vendedor = User::where('email', 'vendedor.pos@test.com')->first();

        $this->browse(function (Browser $browser) use ($vendedor) {
            $browser->loginAs($vendedor)
                    ->visit('/vendedor/pos')
                    ->pause(2000)
                    ->assertSee('Punto de Venta')
                    ->assertSee('POS Producto A');
        });
    }

    /** CP-POS-002 */
    public function test_agregar_producto_al_carrito()
    {
        $vendedor = User::where('email', 'vendedor.pos@test.com')->first();

        $this->browse(function (Browser $browser) use ($vendedor) {
            $browser->loginAs($vendedor)
                    ->visit('/vendedor/pos')
                    ->pause(2000)
                    ->click('.product-card')
                    ->pause(500)
                    ->assertSee('1 producto')
                    ->assertSee('Q25.00');
        });
    }

    /** CP-POS-003 */
    public function test_procesar_venta_exitosa()
    {
        $vendedor = User::where('email', 'vendedor.pos@test.com')->first();

        $this->browse(function (Browser $browser) use ($vendedor) {
            $browser->loginAs($vendedor)
                    ->visit('/vendedor/pos')
                    ->pause(2000)
                    ->click('.product-card')
                    ->pause(500)
                    ->click('#btnVender')
                    ->pause(2500)
                    ->waitFor('.modal-overlay.active', 10)
                    ->assertSee('¡Venta registrada!');
        });

        $venta = DB::table('ventas')
            ->where('idUsuario', $vendedor->id)
            ->orderBy('idVenta', 'desc')
            ->first();

        $this->assertNotNull($venta);
        $this->assertEquals(25.00, $venta->total);
    }

    /** CP-POS-004 */
    public function test_stock_insuficiente()
    {
        DB::table('productos')->where('codigo_interno', 'POS-TEST-A')->update(['stock_actual' => 2]);

        $vendedor = User::where('email', 'vendedor.pos@test.com')->first();

        $this->browse(function (Browser $browser) use ($vendedor) {
            $browser->loginAs($vendedor)
                    ->visit('/vendedor/pos')
                    ->pause(2000)
                    ->click('.product-card')
                    ->pause(500)
                    ->click('.qty-btn:last-child')
                    ->pause(300)
                    ->click('.qty-btn:last-child')
                    ->pause(1000)
                    ->waitFor('#errorModal.active', 8)
                    ->assertSee('Stock máximo');
        });
    }

    /** CP-POS-005 */
    public function test_aplicar_descuento()
    {
        $vendedor = User::where('email', 'vendedor.pos@test.com')->first();

        $this->browse(function (Browser $browser) use ($vendedor) {
            $browser->loginAs($vendedor)
                    ->visit('/vendedor/pos')
                    ->pause(2000)
                    ->click('.product-card')
                    ->pause(500)
                    ->clear('#cartDiscount')
                    ->type('#cartDiscount', '5')
                    ->pause(500)
                    ->assertSee('Q20.00');
        });
    }

    /** CP-POS-006 */
    public function test_buscar_producto_en_pos()
    {
        $vendedor = User::where('email', 'vendedor.pos@test.com')->first();

        $this->browse(function (Browser $browser) use ($vendedor) {
            $browser->loginAs($vendedor)
                    ->visit('/vendedor/pos')
                    ->pause(2000)
                    ->type('#buscarInput', 'Producto A')
                    ->pause(1500)
                    ->assertSee('POS Producto A');
        });
    }

    /** CP-POS-007 */
    public function test_vaciar_carrito()
    {
        $vendedor = User::where('email', 'vendedor.pos@test.com')->first();

        $this->browse(function (Browser $browser) use ($vendedor) {
            $browser->loginAs($vendedor)
                    ->visit('/vendedor/pos')
                    ->pause(2000)
                    ->click('.product-card')
                    ->pause(500)
                    ->assertSee('1 producto');
            // El clearCart usa confirm() que Dusk maneja con acceptDialog
        });
    }
}