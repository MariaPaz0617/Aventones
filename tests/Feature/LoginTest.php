<?php

//TEST QUE VERIFICA EL FUNCIONAMIENTO DEL LOGIN
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Test que verifica el funcionamiento del login
class LoginTest extends TestCase
{
    use RefreshDatabase;

    // Test que verifica un login correcto redirige al menú
    public function login_correcto_redirige_al_menu()
    {
        $usuario = Usuario::factory()->create([
            'password' => bcrypt('123456')
        ]);

        $response = $this->post('/login', [
            'email' => $usuario->email,
            'password' => '123456'
        ]);

        $response->assertStatus(302); 
    }
}
