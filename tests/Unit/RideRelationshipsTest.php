<?php

//CALCULO DE RELCOINES DEL MODELO RIDE CON OTROS MODELOS

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Ride;
use App\Models\Usuario;
use App\Models\Vehiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RideRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    // Test que verifica la relación entre Ride y Usuario (chofer)
    public function ride_pertenece_a_un_chofer()
    {
        $chofer = Usuario::factory()->create(['rol' => 'chofer']);
        $ride = Ride::factory()->create(['usuario_id' => $chofer->id]);

        $this->assertInstanceOf(Usuario::class, $ride->usuario);
    }

    // Test que verifica la relación entre Ride y Vehiculo
    public function ride_pertenece_a_un_vehiculo()
    {
        $vehiculo = Vehiculo::factory()->create();
        $ride = Ride::factory()->create(['vehiculo_id' => $vehiculo->id]);

        $this->assertInstanceOf(Vehiculo::class, $ride->vehiculo);
    }
}
