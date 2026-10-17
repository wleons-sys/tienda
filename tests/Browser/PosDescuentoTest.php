<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PosDescuentoTest extends DuskTestCase
{
    protected $vendedorEmail = 'vendedor@gmail.com';
    protected $vendedorPassword = 'password';

    protected function setUp(): void
    {
        parent::setUp();
        $this->crearDatosPrueba();
    }

    protected function crearDatosPrueba()
    {
        DB::table('productos')->where('codigo_interno', 'LIKE', 'TEST-%')->delete();
        DB::table('categorias')->where('nombre', 'TEST CATEGORIA')->delete();

        $idCategoria = DB::table('categorias')->insertGetId([
            'nombre' => 'TEST CATEGORIA',
            'descripcion' => 'Para pruebas',
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('productos')->insert([
            'nombre' => 'TEST Producto A',
            'idCategoria' => $idCategoria,
            'precio' => 25.00,
            'stock_actual' => 100,
            'stock_minimo' => 10,
            'codigo_interno' => 'TEST-A001',
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    protected function loginVendedor(Browser $browser)
    {
        return $browser->visit('/')
                ->pause(1000)
                ->click('.btn-main')
                ->pause(800)
                ->type('email', $this->vendedorEmail)
                ->type('password', $this->vendedorPassword)
                ->click('button[type="submit"]')
                ->waitForLocation('/vendedor/dashboard', 15);
    }

    /**
     * CP-POS-004: Aplicar descuento
     */
    public function test_aplicar_descuento()
    {
        $vendedor = User::where('email', $this->vendedorEmail)->first();

        $this->browse(function (Browser $browser) {
            $this->loginVendedor($browser);

            $browser->visit('/vendedor/pos')
                    ->pause(1500)
                    ->click('.product-card')
                    ->pause(500)
                    ->assertSee('Q25.00')
                    ->clear('#cartDiscount')
                    ->type('#cartDiscount', '5')
                    ->pause(500)
                    ->assertSee('Q20.00')
                    ->click('#btnVender')
                    ->pause(2000)
                    ->waitFor('.modal-overlay.active', 10)
                    ->assertSee('¡Venta registrada!')
                    ->assertSee('Q20.00');
        });

        $venta = DB::table('ventas')->where('idUsuario', $vendedor->id)->orderBy('idVenta', 'desc')->first();
        $this->assertEquals(5.00, $venta->descuento);
        $this->assertEquals(20.00, $venta->total);
    }
}