<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;
use FakerRestaurant\Provider\pt_BR\Restaurant as RestaurantFakerBR;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fakerRestaurant = Faker::create();
        $fakerRestaurant->addProvider(new RestaurantFakerBR($fakerRestaurant));

        return [
            'name' => $fakerRestaurant->foodName(),
            'description' => fake()->text(100),
            'price' => fake()->randomFloat(2, 10, 50),
        ];
    }
}
