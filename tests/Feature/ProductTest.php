<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{

    use RefreshDatabase;

    private Restaurant $restaurant;
    protected function setUp(): void
    {
        parent::setUp();
        $this->restaurant = Restaurant::factory()->create();
    }

    /**
     * @test
     * @return void
     */
    public function shouldReturnAProductById(): void
    {
        $product = Product::factory()->create(['restaurant_id' => $this->restaurant->id]);
        $response = $this->getJson("/api/product/{$product['id']}");

        $json = $response->getOriginalContent();
        $response->assertStatus(200);
        $this->assertEquals($product->toArray(), $json);
    }

    /**
     * @test
     * @return void
     */
    public function shouldCreateAProductSucessfully(): void
    {
        $product = Product::factory()->make(['restaurant_id' => $this->restaurant->id]);

        $response = $this->postJson("/api/product/", [
            'name' => $product['name'],
            'description' => $product['description'],
            'price' => $product['price'],
            'restaurant_id' => $this->restaurant->id,
        ]);


        $response->assertStatus(201);
        $this->assertNotEmpty($response['id']);
        $this->assertSame($product['name'], $response['name']);
        $this->assertSame($product['description'], $response["description"]);
        $this->assertSame($product['price'], $response["price"]);
        $this->assertSame($product['restaurant_id'], $response['restaurant_id']);
        $this->assertNotEmpty($response['created_at']);
        $this->assertNotEmpty($response['updated_at']);
        $this->assertDatabaseCount('products', 1);
    }

    /**
     * @test
     * @return void
     */
    public function shouldUpdateAProductSucessfully(): void
    {
        $product = Product::factory()->create(['restaurant_id' => $this->restaurant->id]);
        $product2 = Product::factory()->create(['restaurant_id' => $this->restaurant->id]);

        $newValues = [
            'name' => "New Product",
            'description' => "New Product",
            'price' => 12.20,
        ];

        $response = $this->putJson("/api/product/{$product['id']}", $newValues);

        $response->assertStatus(200);
        $this->assertEquals($newValues['name'], $response["name"]);
        $this->assertEquals($newValues['description'], $response["description"]);
        $this->assertEquals($newValues['price'], $response["price"]);

        $this->assertEquals($product2->toArray(), Product::find($product2["id"])->toArray());

    }

    /**
     * @test
     * @return void
     */
    public function shouldDeleteAProductSucessfully(): void
    {
        $product = Product::factory()->create(['restaurant_id' => $this->restaurant->id]);
        $product2 = Product::factory()->create(['restaurant_id' => $this->restaurant->id]);

        $response = $this->deleteJson("/api/product/{$product['id']}");

        $response->assertStatus(204);
        $this->assertEmpty(Product::find($product["id"]));
        $this->assertNotEmpty(Product::find($product2["id"]));
    }
}
