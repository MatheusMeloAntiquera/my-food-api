<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function shouldReturnAllUsers(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->getJson("/api/user");
        $response->assertStatus(200);

        $json = $response->getOriginalContent();
        $this->assertNotEmpty($json);
        $this->assertIsArray($json);
        $this->assertCount(2, $json);
        $this->assertEquals($user->toArray(), $json[0]);
        $this->assertEquals($user2->toArray(), $json[1]);
    }

    /**
     * @test
     * @return void
     */
    public function shouldReturnAUserById(): void
    {
        $user = User::factory()->create();
        $response = $this->getJson("/api/user/{$user['id']}");

        $json = $response->getOriginalContent();
        $response->assertStatus(200);
        $this->assertEquals($user->toArray(), $json);
    }

    /**
     * @test
     * @return void
     */
    public function shouldCreateAUserSucessfully(): void
    {
        $user = User::factory()->make();

        $response = $this->postJson("/api/user/", [
            "name" => $user["name"],
            "email" => $user["email"],
            "password" => $user["password"],
        ]);

        $response->assertStatus(201);
        $this->assertEquals($user["name"], $response["name"]);
        $this->assertEquals($user["email"], $response["email"]);
        $this->assertEquals(null, $response["email_verified_at"]);
        $this->assertNotEmpty($response["created_at"]);
        $this->assertNotEmpty($response["updated_at"]);
    }

    /**
     * @test
     * @return void
     */
    public function shouldUpdateAUserSucessfully(): void
    {
        $user = User::factory()->create();

        $newName = fake()->name();
        $newEmail = fake()->unique()->safeEmail();
        $response = $this->putJson("/api/user/{$user['id']}", [
            "name" => $newName,
            "email" => $newEmail,
            "password" => $user["password"],
        ]);

        $response->assertStatus(200);
        $this->assertEquals($newName, $response["name"]);
        $this->assertEquals($newEmail, $response["email"]);
    }

    /**
     * @test
     * @return void
     */
    public function shouldDeleteAUserSucessfully(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->deleteJson("/api/user/{$user['id']}");

        $response->assertStatus(204);
        $this->assertEmpty(User::find($user["id"]));
        $this->assertNotEmpty(User::find($user2["id"]));
    }
}
