<?php

namespace Tests\Feature;

use App\Models\Usuario;
use App\Models\Grupo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GrupoManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_groups(): void
    {
        $admin = Usuario::create([
            'nombre' => 'Admin User',
            'username' => 'admin_test',
            'password' => bcrypt('password123'),
            'rol' => 'ADMIN',
            'activo' => true,
        ]);

        $this->actingAs($admin);

        // 1. Create a group
        $response = $this->post(route('grupos.store'), [
            'nombre' => '4A',
        ]);

        $response->assertRedirect(route('grupos.index'));
        $this->assertDatabaseHas('grupos', [
            'nombre' => '4A',
        ]);

        // 2. List groups
        $response = $this->get(route('grupos.index'));
        $response->assertStatus(200);
        $response->assertSee('4A');

        // 3. Delete group
        $grupo = Grupo::where('nombre', '4A')->first();
        $response = $this->delete(route('grupos.destroy', $grupo));
        $response->assertRedirect(route('grupos.index'));
        $this->assertDatabaseMissing('grupos', [
            'nombre' => '4A',
        ]);
    }

    public function test_profesor_cannot_manage_groups(): void
    {
        $profesor = Usuario::create([
            'nombre' => 'Profesor User',
            'username' => 'prof_test',
            'password' => bcrypt('password123'),
            'rol' => 'PROFESOR',
            'activo' => true,
        ]);

        $this->actingAs($profesor);

        $response = $this->post(route('grupos.store'), [
            'nombre' => '4A',
        ]);
        $response->assertStatus(403);

        $response = $this->get(route('grupos.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_create_group_with_profesor(): void
    {
        $admin = Usuario::create([
            'nombre' => 'Admin User',
            'username' => 'admin_test2',
            'password' => bcrypt('password123'),
            'rol' => 'ADMIN',
            'activo' => true,
        ]);

        $profesor = Usuario::create([
            'nombre' => 'Profesor Test',
            'username' => 'prof_test2',
            'password' => bcrypt('password123'),
            'rol' => 'PROFESOR',
            'activo' => true,
        ]);

        $this->actingAs($admin);

        // Create group with professor
        $response = $this->post(route('grupos.store'), [
            'nombre' => '8VSC2',
            'profesor_id' => $profesor->id,
        ]);

        $response->assertRedirect(route('grupos.index'));
        $this->assertDatabaseHas('grupos', [
            'nombre' => '8VSC2',
            'profesor_id' => $profesor->id,
        ]);

        // Access index and see professor name
        $response = $this->get(route('grupos.index'));
        $response->assertSee('Profesor Test');
    }
}
