<?php

namespace Tests\Feature;

use App\Models\Courier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourierTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_200_status_code(): void
    {
        $response = $this->getJson('/api/v1/couriers');

        $response->assertStatus(200);
    }

    public function test_index_returns_paginated_data_structure(): void
    {
        Courier::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/couriers');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'email', 'phone', 'level', 'created_at', 'updated_at'],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_index_returns_couriers_sorted_by_name_ascending_by_default(): void
    {
        Courier::factory()->create(['name' => 'Zack']);
        Courier::factory()->create(['name' => 'Andy']);
        Courier::factory()->create(['name' => 'Bobby']);

        $response = $this->getJson('/api/v1/couriers');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals('Andy', $data[0]['name']);
        $this->assertEquals('Bobby', $data[1]['name']);
        $this->assertEquals('Zack', $data[2]['name']);
    }

    public function test_index_can_filter_by_sort_latest(): void
    {
        Courier::factory()->create(['name' => 'Alpha', 'created_at' => now()->subDay()]);
        Courier::factory()->create(['name' => 'Beta', 'created_at' => now()]);

        $response = $this->getJson('/api/v1/couriers?sort=latest');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals('Beta', $data[0]['name']);
    }

    public function test_index_can_search_by_single_keyword(): void
    {
        Courier::factory()->create(['name' => 'John Doe']);
        Courier::factory()->create(['name' => 'Jane Smith']);

        $response = $this->getJson('/api/v1/couriers?search=John');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('John Doe', $response->json('data.0.name'));
    }

    public function test_index_can_search_by_multiple_keywords(): void
    {
        Courier::factory()->create(['name' => 'Budiono Hadi Agung']);
        Courier::factory()->create(['name' => 'Budi Santoso']);
        Courier::factory()->create(['name' => 'John Doe']);

        $response = $this->getJson('/api/v1/couriers?search=budi+agung');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Budiono Hadi Agung', $response->json('data.0.name'));
    }

    public function test_index_can_filter_by_level(): void
    {
        Courier::factory()->create(['level' => 1]);
        Courier::factory()->create(['level' => 2]);
        Courier::factory()->create(['level' => 3]);

        $response = $this->getJson('/api/v1/couriers?level=1,2');

        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));
    }

    public function test_show_returns_200_with_courier_data(): void
    {
        $courier = Courier::factory()->create();

        $response = $this->getJson("/api/v1/couriers/{$courier->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $courier->id,
                    'name' => $courier->name,
                    'email' => $courier->email,
                    'phone' => $courier->phone,
                    'level' => $courier->level,
                ],
            ]);
    }

    public function test_show_returns_404_for_non_existent_courier(): void
    {
        $response = $this->getJson('/api/v1/couriers/99999');

        $response->assertStatus(404);
    }

    public function test_store_returns_201_when_valid_data_is_provided(): void
    {
        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '081234567890',
            'level' => 3,
        ];

        $response = $this->postJson('/api/v1/couriers', $payload);

        $response->assertStatus(201);
        $this->assertDatabaseHas('couriers', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
        ]);
    }

    public function test_store_returns_422_when_name_is_missing(): void
    {
        $payload = [
            'email' => 'john@example.com',
            'phone' => '081234567890',
            'level' => 3,
        ];

        $response = $this->postJson('/api/v1/couriers', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_store_returns_422_when_email_is_invalid(): void
    {
        $payload = [
            'name' => 'John Doe',
            'email' => 'not-an-email',
            'phone' => '081234567890',
            'level' => 3,
        ];

        $response = $this->postJson('/api/v1/couriers', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_store_returns_422_when_email_is_not_unique(): void
    {
        Courier::factory()->create(['email' => 'existing@example.com']);

        $payload = [
            'name' => 'John Doe',
            'email' => 'existing@example.com',
            'phone' => '081234567890',
            'level' => 3,
        ];

        $response = $this->postJson('/api/v1/couriers', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_store_returns_422_when_level_is_out_of_range(): void
    {
        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '081234567890',
            'level' => 6,
        ];

        $response = $this->postJson('/api/v1/couriers', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['level']);
    }

    public function test_update_returns_200_when_valid_data_is_provided(): void
    {
        $courier = Courier::factory()->create();

        $payload = [
            'name' => 'Updated Name',
            'email' => $courier->email,
            'phone' => '0999888777',
            'level' => 4,
        ];

        $response = $this->putJson("/api/v1/couriers/{$courier->id}", $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('couriers', [
            'id' => $courier->id,
            'name' => 'Updated Name',
            'phone' => '0999888777',
        ]);
    }

    public function test_update_returns_404_for_non_existent_courier(): void
    {
        $response = $this->putJson('/api/v1/couriers/99999', [
            'name' => 'Test',
        ]);

        $response->assertStatus(404);
    }

    public function test_update_allows_updating_email_to_same_value_for_same_courier(): void
    {
        $courier = Courier::factory()->create();

        $payload = [
            'email' => $courier->email,
        ];

        $response = $this->putJson("/api/v1/couriers/{$courier->id}", $payload);

        $response->assertStatus(200);
    }

    public function test_destroy_returns_204_when_courier_is_deleted(): void
    {
        $courier = Courier::factory()->create();

        $response = $this->deleteJson("/api/v1/couriers/{$courier->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('couriers', [
            'id' => $courier->id,
        ]);
    }

    public function test_destroy_returns_404_for_non_existent_courier(): void
    {
        $response = $this->deleteJson('/api/v1/couriers/99999');

        $response->assertStatus(404);
    }
}
