<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_only_create_profesor(): void
    {
        $admin = Usuario::create([
            'nombre' => 'Admin User',
            'username' => 'admin_test',
            'password' => bcrypt('password123'),
            'rol' => 'ADMIN',
            'activo' => true,
        ]);

        $this->actingAs($admin);

        // Attempting to create with role ALUMNO should still save it as PROFESOR (role logic forces it or validates it)
        $response = $this->post(route('usuarios.store'), [
            'nombre' => 'New Professor',
            'username' => 'new_prof',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'rol' => 'ALUMNO', // Will be ignored and forced to PROFESOR by controller
        ]);

        $response->assertRedirect(route('usuarios.index'));
        $this->assertDatabaseHas('usuarios', [
            'username' => 'new_prof',
            'rol' => 'PROFESOR',
            'profesor_id' => null,
        ]);
    }

    public function test_profesor_can_only_create_alumno_with_profesor_id(): void
    {
        $profesor = Usuario::create([
            'nombre' => 'Profesor User',
            'username' => 'prof_test',
            'password' => bcrypt('password123'),
            'rol' => 'PROFESOR',
            'activo' => true,
        ]);

        $this->actingAs($profesor);

        $response = $this->post(route('usuarios.store'), [
            'nombre' => 'New Student',
            'username' => 'new_student',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'grupo' => '3A',
        ]);

        $response->assertRedirect(route('usuarios.index'));
        $this->assertDatabaseHas('usuarios', [
            'username' => 'new_student',
            'rol' => 'ALUMNO',
            'profesor_id' => $profesor->id,
            'grupo' => '3A',
        ]);
    }
}
