<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;

class InventoryManagementTest extends FeatureTestCase
{
    public function test_admin_can_create_edit_and_delete_inventory_products(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        $this->actingAs($admin)->post(route('inventory.store'), [
            'name' => 'Cuero negro',
            'size' => 'Único',
            'price' => 12.50,
            'stock' => 3,
            'category' => 'cueros',
        ])->assertRedirect(route('inventory.index'));

        $product = Product::where('name', 'Cuero negro')->firstOrFail();
        $this->assertDatabaseHas('inventory_movements', [
            'product_id' => $product->id,
            'quantity' => 3,
            'type' => 'in',
        ]);

        $this->put(route('inventory.update', $product), [
            'name' => $product->name,
            'size' => $product->size,
            'price' => 12.50,
            'stock' => 5,
            'category' => 'cueros',
        ])->assertRedirect(route('inventory.index'));

        $this->assertDatabaseHas('inventory_movements', [
            'product_id' => $product->id,
            'quantity' => 2,
            'type' => 'in',
            'reason' => 'Ajuste desde edición de producto',
        ]);

        $this->delete(route('inventory.destroy', $product))
            ->assertRedirect(route('inventory.index'));

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_inventory_rejects_unknown_categories(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        $this->actingAs($admin)->post(route('inventory.store'), [
            'name' => 'Material',
            'size' => 'Único',
            'price' => 1,
            'stock' => 1,
            'category' => 'desconocida',
        ])->assertSessionHasErrors('category');

        $this->assertDatabaseMissing('products', ['name' => 'Material']);
    }
}