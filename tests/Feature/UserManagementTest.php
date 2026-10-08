<?php

namespace Tests\Feature;

use App\Models\User;

class UserManagementTest extends FeatureTestCase
{
    public function test_admin_can_create_edit_deactivate_and_reactivate_users(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Nueva persona',
            'email' => 'new@example.test',
            'role' => 'user',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('users.index'));

        $user = User::where('email', 'new@example.test')->firstOrFail();
        $this->assertSame('user', $user->role);
        $this->get(route('users.index'))->assertOk()->assertSee('Nueva persona')->assertSee('Activo');

        $this->put(route('users.update', $user), [
            'name' => 'Persona editada',
            'email' => 'new@example.test',
            'role' => 'admin',
            'password' => '',
            'password_confirmation' => '',
        ])->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Persona editada', 'role' => 'admin']);

        $this->delete(route('users.destroy', $user))->assertRedirect();
        $this->assertSoftDeleted('users', ['id' => $user->id]);
        $this->get(route('users.index'))->assertSee('Inactivo');

        $this->patch(route('users.restore', $user->id))->assertRedirect();
        $this->assertNotSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_admin_role_update_is_persisted(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => 'password123',
            'role' => 'admin',
        ]);
        $user = User::create([
            'name' => 'User',
            'email' => 'user@example.test',
            'password' => 'password123',
            'role' => 'user',
        ]);

        $this->actingAs($admin)->patch(route('users.role', $user), ['role' => 'admin'])
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['id' => $user->id, 'role' => 'admin']);
    }
}