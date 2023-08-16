<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Enums\StatusOrder;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Sequence;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    private User $customer;
    private Restaurant $restaurant;
    /**
     *
     * @var array<Product>
     */
    private $products;
    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = User::factory()->create();
        $this->restaurant = Restaurant::factory()->create();
        $this->products = Product::factory()->count(3)->create(['restaurant_id' => $this->restaurant->id]);
    }
    /**
     * @test
     * @return void
     */
    public function shouldDoAnOrderSucessfully(): void
    {
        $response = $this->requestAnOrder();

        $response->assertStatus(201);
        $this->assertNotEmpty($response['id']);
        $this->assertEquals($this->customer['id'], $response['customer_id']);
        $this->assertEquals($this->restaurant['id'], $response['restaurant_id']);
        $this->assertEquals($this->getTotal(), $response['total']);
        $this->assertNotEmpty($response['created_at']);
        $this->assertNotEmpty($response['updated_at']);
    }

    /**
     * @test
     * @return void
     */
    public function shouldReturnAnOrderById(): void
    {
        $order = $this->requestAnOrder();
        $response = $this->getJson("/api/order/{$order['id']}");

        $response->assertStatus(200);
        $this->assertEquals($order['id'], $response['id']);
        $this->assertEquals($this->customer['id'], $response['customer_id']);
        $this->assertEquals($this->restaurant['id'], $response['restaurant_id']);
        $this->assertEquals($this->getTotal(), $response['total']);
        $this->assertEquals(StatusOrder::PENDING->value, $response['status']);
        $this->assertNotEmpty($response['created_at']);
        $this->assertNotEmpty($response['updated_at']);

        $this->assertCount(3, $response['items']);

        $this->assertEquals($this->products[2]['name'], $response['items'][0]['name']);
        $this->assertEquals($this->products[2]['price'], $response['items'][0]['price']);
        $this->assertEquals(2, $response['items'][0]['amount']);

        $this->assertEquals($this->products[0]['name'], $response['items'][1]['name']);
        $this->assertEquals($this->products[0]['price'], $response['items'][1]['price']);
        $this->assertEquals(1, $response['items'][1]['amount']);

        $this->assertEquals($this->products[1]['name'], $response['items'][2]['name']);
        $this->assertEquals($this->products[1]['price'], $response['items'][2]['price']);
        $this->assertEquals(3, $response['items'][2]['amount']);

    }

    /**
     * @test
     * @return void
     */
    public function shouldReturnAllRestaurantOrders(): void
    {

        $restaurant2 = Restaurant::factory()->create();

        $orders = Order::factory()->count(5)->state(
            new Sequence(
                ['restaurant_id' => $this->restaurant['id']],
                ['restaurant_id' => $restaurant2['id']],
            )
        )->create(['customer_id' => $this->customer['id'], 'status' => StatusOrder::PENDING->value]);

        $response = $this->getJson("/api/restaurant/{$this->restaurant['id']}/orders");
        $ordersRestaurant = $orders->where('restaurant_id', $this->restaurant['id'])->toArray();

        $response->assertStatus(200);
        $this->assertCount(3, $response->getOriginalContent());
        $this->assertEquals(array_values($ordersRestaurant), $response->getOriginalContent());

        $response2 = $this->getJson("/api/restaurant/{$restaurant2['id']}/orders");
        $ordersRestaurant2 = $orders->where('restaurant_id', $restaurant2['id'])->toArray();

        $response2->assertStatus(200);
        $this->assertCount(2, $response2->getOriginalContent());
        $this->assertEquals(array_values($ordersRestaurant2), $response2->getOriginalContent());

    }

    /**
     * @test
     * @return void
     */
    public function shouldReturnAllCustomerOrders(): void
    {
        $restaurant2 = Restaurant::factory()->create();

        $orders = Order::factory()->count(5)->state(
            new Sequence(
                ['restaurant_id' => $this->restaurant['id']],
                ['restaurant_id' => $restaurant2['id']],
            )
        )->create(['customer_id' => $this->customer['id'], 'status' => StatusOrder::PENDING->value]);

        $response = $this->getJson("/api/user/{$this->customer['id']}/orders");

        $response->assertStatus(200);
        $this->assertCount(5, $response->getOriginalContent());
        $this->assertEquals(array_values($orders->toArray()), $response->getOriginalContent());
    }

    private function requestAnOrder()
    {
        return $this->postJson("/api/order/", [
            "customer_id" => $this->customer['id'],
            "restaurant_id" => $this->restaurant['id'],
            "items" => [
                [
                    "name" => $this->products[2]['name'],
                    "price" => $this->products[2]['price'],
                    "amount" => 2
                ],
                [
                    "name" => $this->products[0]['name'],
                    "price" => $this->products[0]['price'],
                    "amount" => 1
                ],
                [
                    "name" => $this->products[1]['name'],
                    "price" => $this->products[1]['price'],
                    "amount" => 3
                ],
            ]
        ]);
    }

    private function getTotal()
    {
        return ($this->products[2]['price'] * 2) + ($this->products[0]['price']) + ($this->products[1]['price'] * 3);
    }
}
