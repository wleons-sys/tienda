<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PosAgregarProductoTest extends DuskTestCase
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
        // Limpiar productos TEST anteriores
        DB::table('productos')->where('codigo_interno', 'LIKE', 'TEST-%')->delete();
        DB::table('categorias')->where('nombre', 'TEST CATEGORIA')->delete();

        // Crear categoría
        $idCategoria = DB::table('categorias')->insertGetId([
            'nombre' => 'TEST CATEGORIA',
            'descripcion' => 'Para pruebas',
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear productos
        DB::table('productos')->insert([
            [
                'nombre' => 'TEST Producto A',
                'idCategoria' => $idCategoria,
                'precio' => 25.00,
                'stock_actual' => 100,
                'stock_minimo' => 10,
                'codigo_interno' => 'TEST-A001',
                'activo' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'TEST Producto B',
                'idCategoria' => $idCategoria,
                'precio' => 15.50,
                'stock_actual' => 50,
                'stock_minimo' => 5,
                'codigo_interno' => 'TEST-B001',
                'activo' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
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
     * CP-POS-001: Agregar producto al carrito
     */
    public function test_agregar_producto_al_carrito()
    {
        $this->browse(function (Browser $browser) {
            $this->loginVendedor($browser);

            $browser->visit('/vendedor/pos')
                    ->pause(1500)
                    ->assertSee('Punto de Venta')
                    ->assertSee('TEST Producto A')
                    ->click('.product-card')
                    ->pause(500)
                    ->assertSee('1 producto')
                    ->assertSee('Q25.00')
                    ->click('.product-card:nth-child(2)')
                    ->pause(500)
                    ->assertSee('2 productos');
        });
    }
}