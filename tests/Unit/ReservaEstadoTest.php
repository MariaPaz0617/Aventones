<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Reserva;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservaEstadoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function una_reserva_comienza_en_estado_pendiente()
    {
        $reserva = Reserva::factory()->create();

        $this->assertEquals('PENDIENTE', $reserva->estado);
    }

    /** @test */
    public function puede_cambiar_de_estado_a_aceptada()
    {
        $reserva = Reserva::factory()->create();
        $reserva->estado = 'ACEPTADA';
        $reserva->save();

        $this->assertEquals('ACEPTADA', $reserva->fresh()->estado);
    }
}
