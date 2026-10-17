<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ReporteTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        User::where('email', 'admin.rep@test.com')->delete();

        $admin = User::create([
            'name' => 'Admin Rep',
            'email' => 'admin.rep@test.com',
            'password' => bcrypt('password'),
            'activo' => 1,
        ]);
        DB::table('roles_usuarios')->insert(['idUsuario' => $admin->id, 'idRol' => 1, 'created_at' => now(), 'updated_at' => now()]);
    }

    /** CP-REP-001 */
    public function test_panel_reportes()
    {
        $admin = User::where('email', 'admin.rep@test.com')->first();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/reportes')
                    ->pause(2000)
                    ->assertSee('Reportes y Análisis');
        });
    }

    /** CP-REP-002 */
    public function test_reporte_ventas_fecha()
    {
        $admin = User::where('email', 'admin.rep@test.com')->first();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/reportes/ventas-fecha')
                    ->pause(3000)
                    ->assertSee('Ventas por Fecha')
                    ->assertPresent('#chartVentas');
        });
    }

    /** CP-REP-003 */
    public function test_reporte_ventas_categoria()
    {
        $admin = User::where('email', 'admin.rep@test.com')->first();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/reportes/ventas-categoria')
                    ->pause(3000)
                    ->assertSee('Ventas por Categoría')
                    ->assertPresent('#chartCategorias');
        });
    }

    /** CP-REP-004 */
    public function test_reporte_productos_vendidos()
    {
        $admin = User::where('email', 'admin.rep@test.com')->first();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/reportes/productos-vendidos')
                    ->pause(3000)
                    ->assertSee('Productos Más Vendidos')
                    ->assertPresent('#chartProductos');
        });
    }

    /** CP-REP-005 */
    public function test_reporte_stock_critico()
    {
        $admin = User::where('email', 'admin.rep@test.com')->first();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/reportes/stock-critico')
                    ->pause(2000)
                    ->assertSee('Stock Crítico');
        });
    }

    /** CP-REP-006 */
    public function test_reporte_movimientos()
    {
        $admin = User::where('email', 'admin.rep@test.com')->first();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/reportes/movimientos')
                    ->pause(2000)
                    ->assertSee('Movimientos de Inventario');
        });
    }

    /** CP-REP-007 */
    public function test_exportar_pdf_ventas()
    {
        $admin = User::where('email', 'admin.rep@test.com')->first();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/reportes/ventas-fecha')
                    ->pause(2000)
                    ->assertSee('Exportar PDF')
                    ->assertPresent('a[target="_blank"]');
        });
    }
}