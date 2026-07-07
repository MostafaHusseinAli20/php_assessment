<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_can_get_products(): void
    {
        Product::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/products');

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data',
                'meta',
            ]);
    }

    public function test_can_create_product(): void
    {
        $response = $this->postJson('/api/v1/products', [
            'sku' => 'SKU-100',
            'name' => 'Keyboard',
            'description' => 'Mechanical',
            'price' => 100,
            'stock_quantity' => 20,
            'low_stock_threshold' => 5,
            'status' => 'active',
        ]);

        $response
            ->assertCreated()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('products', [
            'sku' => 'SKU-100',
        ]);
    }

    public function test_can_show_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/v1/products/{$product->id}");

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
            ]);
    }

    public function test_can_update_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->putJson("/api/v1/products/{$product->id}", [
            'name' => 'New Name',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'New Name',
        ]);
    }

    public function test_can_delete_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/v1/products/{$product->id}");

        $response->assertOk();

        $this->assertSoftDeleted('products', [
            'id' => $product->id,
        ]);
    }

    public function test_can_increment_stock(): void
    {
        $product = Product::factory()->create([
            'stock_quantity' => 10,
        ]);

        $response = $this->putJson(
            "/api/v1/products/{$product->id}/adjust-stock",
            [
                'action' => 'increment',
                'quantity' => 5,
            ]
        );

        $response->assertOk();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 15,
        ]);
    }

    public function test_cannot_decrement_more_than_stock(): void
    {
        $product = Product::factory()->create([
            'stock_quantity' => 3,
        ]);

        $response = $this->putJson(
            "/api/v1/products/{$product->id}/adjust-stock",
            [
                'action' => 'decrement',
                'quantity' => 5,
            ]
        );

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_can_get_low_stock_products(): void
    {
        Product::factory()->create([
            'stock_quantity' => 2,
            'low_stock_threshold' => 5,
        ]);

        Product::factory()->create([
            'stock_quantity' => 20,
            'low_stock_threshold' => 5,
        ]);

        $response = $this->getJson('/api/v1/products/low-stock');

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonCount(1, 'data');
    }
}
