<?php

//TEST QUE VERIFICA LA CREACION DE UN RIDE POR PARTE DE UN CHOFER
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Usuario;
use App\Models\Vehiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RideCreationTest extends TestCase
{
    use RefreshDatabase;

    // Test que verifica que un chofer puede crear un ride
    public function un_chofer_puede_crear_un_ride()
    {
        $chofer = Usuario::factory()->create(['rol' => 'chofer']);
        $vehiculo = Vehiculo::factory()->create(['usuario_id' => $chofer->id]);

        $response = $this->post('/rides/registrar', [
            'usuario_id' => $chofer->id,
            'vehiculo_id' => $vehiculo->id,
            'nombre' => 'Viaje a Palmares',
            'lugar_salida' => 'San José',
            'lugar_llegada' => 'Palmares',
            'fecha' => now()->addDay()->toDateString(),
            'hora' => '10:00',
            'costo' => 3000,
            'cantidad_espacios' => 4
        ]);

        $response->assertStatus(200);
    }
}
