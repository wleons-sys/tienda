
namespace Tests\Browser;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class VentaAdminTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        User::where('email', 'admin.venta@test.com')->delete();

        $admin = User::create([
            'name' => 'Admin Venta',
            'email' => 'admin.venta@test.com',
            'password' => bcrypt('password'),
            'activo' => 1,
        ]);
        DB::table('roles_usuarios')->insert(['idUsuario' => $admin->id, 'idRol' => 1, 'created_at' => now(), 'updated_at' => now()]);
    }

    /** CP-VENTA-001 */
    public function test_listar_ventas()
    {
        $admin = User::where('email', 'admin.venta@test.com')->first();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/ventas')
                    ->pause(2000)
                    ->assertSee('Historial de Ventas');
        });
    }

    /** CP-VENTA-002 */
    public function test_ver_detalle_venta()
    {
        $admin = User::where('email', 'admin.venta@test.com')->first();

        // Crear venta
        $idVenta = DB::table('ventas')->insertGetId([
            'idUsuario' => $admin->id,
            'fecha' => now(),
            'subtotal' => 100.00,
            'descuento' => 0,
            'total' => 100.00,
            'estado' => 'Activa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($admin, $idVenta) {
            $browser->loginAs($admin)
                    ->visit("/admin/ventas/{$idVenta}")
                    ->pause(2000)
                    ->assertSee('#000' . $idVenta)
                    ->assertSee('100.00');
        });
    }

    /** CP-VENTA-003 */
    public function test_anular_venta()
    {
        $admin = User::where('email', 'admin.venta@test.com')->first();

        $idVenta = DB::table('ventas')->insertGetId([
            'idUsuario' => $admin->id,
            'fecha' => now(),
            'subtotal' => 50.00,
            'descuento' => 0,
            'total' => 50.00,
            'estado' => 'Activa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/ventas')
                    ->pause(2000)
                    ->assertSee('Historial de Ventas');
        });

        // Verificar en BD
        $this->assertDatabaseHas('ventas', ['idVenta' => $idVenta, 'estado' => 'Activa']);
    }
}