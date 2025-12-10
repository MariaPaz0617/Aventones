<?php

//CALCULO PARA COMPROBAR LOS ESPACIOS DISPONIBLES EN UN RIDE SEGUN LAS RESERVAS PENDIENTES Y ACEPTADAS
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Ride;
use App\Models\Reserva;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EspaciosDisponiblesTest extends TestCase
{
    use RefreshDatabase;

    // Test que verifica el cálculo de espacios disponibles en un ride
    public function calcula_correctamente_los_espacios_disponibles()
    {
        // Crear un ride con 4 espacios
        $ride = Ride::factory()->create([
            'cantidad_espacios' => 4
        ]);

        // Crear una reserva pendiente
        Reserva::factory()->create([
            'ride_id' => $ride->id,
            'cantidad_asientos' => 2,
            'estado' => 'PENDIENTE'
        ]);

        // Crear una reserva aceptada
        Reserva::factory()->create([
            'ride_id' => $ride->id,
            'cantidad_asientos' => 1,
            'estado' => 'ACEPTADA'
        ]);

        // Cálculo de espacios disponibles
        $pendientes = $ride->reservas()->where('estado', 'PENDIENTE')->sum('cantidad_asientos');
        $aceptadas = $ride->reservas()->where('estado', 'ACEPTADA')->sum('cantidad_asientos');

        $disponibles = $ride->cantidad_espacios - ($pendientes + $aceptadas);

        $this->assertEquals(1, $disponibles);
    }
}
