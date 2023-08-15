<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RestaurantTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function shouldReturnAllRestaurants(): void
    {
        $restaurant = Restaurant::factory()->create();
        $restaurant2 = Restaurant::factory()->create();

        $response = $this->getJson("/api/restaurant");
        $response->assertStatus(200);

        $json = $response->getOriginalContent();

        $this->assertNotEmpty($json);
        $this->assertIsArray($json);
        $this->assertCount(2, $json);
        $this->assertEquals($restaurant->toArray(), $json[0]);
        $this->assertEquals($restaurant2->toArray(), $json[1]);
    }

    /**
     * @test
     * @return void
     */
    public function shouldReturnARestaurantById(): void
    {
        $restaurant = Restaurant::factory()->create();
        $response = $this->getJson("/api/restaurant/{$restaurant['id']}");

        $json = $response->getOriginalContent();
        $response->assertStatus(200);
        $this->assertEquals($restaurant->toArray(), $json);
    }

    /**
     * @test
     * @return void
     */
    public function shouldCreateARestaurantSucessfully(): void
    {
        $restaurant = Restaurant::factory()->make();

        $response = $this->postJson("/api/restaurant/", [
            "name" => $restaurant["name"],
            "email" => $restaurant["email"],
            "password" => $restaurant["password"],
            "postal_code" => $restaurant["postal_code"],
            "address" => $restaurant["address"],
            "city" => $restaurant["city"],
        ]);


        $response->assertStatus(201);
        $this->assertEquals($restaurant["name"], $response["name"]);
        $this->assertEquals($restaurant["email"], $response["email"]);
        $this->assertEquals(null, $response["email_verified_at"]);
        $this->assertNotEmpty($response["created_at"]);
        $this->assertNotEmpty($response["updated_at"]);
        $this->assertDatabaseCount('restaurants', 1);
    }

    /**
     * @test
     * @return void
     */
    public function shouldUpdateARestaurantSucessfully(): void
    {
        $restaurant = Restaurant::factory()->create();
        $restaurant2 = Restaurant::factory()->create();

        $newName = fake()->name();
        $newEmail = fake()->unique()->safeEmail();
        $response = $this->putJson("/api/restaurant/{$restaurant['id']}", [
            "name" => $newName,
            "email" => $newEmail,
            "password" => $restaurant["password"],
            "postal_code" => $restaurant["postal_code"],
            "address" => $restaurant["address"],
            "city" => $restaurant["city"],
        ]);

        $response->assertStatus(200);
        $this->assertEquals($newName, $response["name"]);
        $this->assertEquals($newEmail, $response["email"]);

        $this->assertEquals($restaurant2->toArray(), Restaurant::find($restaurant2["id"])->toArray());

    }

    /**
     * @test
     * @return void
     */
    public function shouldDeleteARestaurantSucessfully(): void
    {
        $restaurant = Restaurant::factory()->create();
        $restaurant2 = Restaurant::factory()->create();

        $response = $this->deleteJson("/api/restaurant/{$restaurant['id']}");

        $response->assertStatus(204);
        $this->assertEmpty(Restaurant::find($restaurant["id"]));
        $this->assertNotEmpty(Restaurant::find($restaurant2["id"]));
    }
}
