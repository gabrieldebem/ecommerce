<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCanListProducts(): void
    {
        $user = User::factory()->create();

        $products = Product::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->get('/api/products')
            ->assertSuccessful()
            ->assertJson(['data' => $products->toArray()]);
    }

    public function testCantLIstProductsFromOtherUser()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $products = Product::factory()->count(3)->create([
            'user_id' => $otherUser->id,
        ]);

        $this->actingAs($user)
            ->get('/api/products')
            ->assertSuccessful()
            ->assertJsonMissing(['data' => $products->toArray()]);
    }

    public function testCanShowProduct(): void
    {
        $user = User::factory()->create();

        $product = Product::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->get("/api/products/{$product->id}")
            ->assertSuccessful()
            ->assertJson($product->toArray());
    }

    public function testCanCreateProduct()
    {
        $user = User::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'description' => $this->faker->text(),
        ];

        $this->actingAs($user)
            ->post('/api/products', $data)
            ->assertSuccessful()
            ->assertJson($data);

        $this->assertDatabaseHas(Product::class, $data);
    }

    public function testCanUpdateProduct()
    {
        $user = User::factory()->create();

        $product = Product::factory()->create([
            'user_id' => $user->id,
        ]);

        $data = [
            'name' => $this->faker->name(),
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'description' => $this->faker->text(),
        ];

        $this->actingAs($user)
            ->put("/api/products/{$product->id}", $data)
            ->assertSuccessful()
            ->assertJson($data);

        $this->assertDatabaseHas(Product::class, $data);
    }

    public function testCanDeleteProduct()
    {
        $user = User::factory()->create();

        $product = Product::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->delete("/api/products/{$product->id}")
            ->assertSuccessful()
            ->assertJson($product->toArray());

        $this->assertDatabaseMissing(Product::class, $product->toArray());
    }

    public function testCantDoNothingUnauthenticated()
    {
        $product = Product::factory()->create();

        $this->get('/api/products')
            ->assertUnauthorized();

        $this->get("/api/products/{$product->id}")
            ->assertUnauthorized();

        $this->post('/api/products')
            ->assertUnauthorized();

        $this->put("/api/products/{$product->id}")
            ->assertUnauthorized();

        $this->delete("/api/products/{$product->id}")
            ->assertUnauthorized();
    }
}
