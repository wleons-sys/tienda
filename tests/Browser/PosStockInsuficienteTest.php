<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PosStockInsuficienteTest extends DuskTestCase
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

        // Producto con stock bajo
        DB::table('productos')->insert([
            'nombre' => 'TEST Producto A',
            'idCategoria' => $idCategoria,
            'precio' => 25.00,
            'stock_actual' => 2,
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
     * CP-POS-003: Stock insuficiente
     */
    public function test_stock_insuficiente()
    {
        $this->browse(function (Browser $browser) {
            $this->loginVendedor($browser);

            $browser->visit('/vendedor/pos')
                    ->pause(1500)
                    ->click('.product-card')
                    ->pause(500)
                    ->click('.qty-btn:last-child')
                    ->pause(300)
                    ->click('.qty-btn:last-child')
                    ->pause(800)
                    ->waitFor('#errorModal.active', 8)
                    ->assertSee('Stock máximo');
        });
    }
}