<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiCategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testAddACategoryWhenNotAuthenticated()
    {
        $response = $this->json('POST', '/api/v1/category', [
            'name' => $this->faker->text(10),
        ]);
        $response->assertUnauthorized();
    }

    public function testAddACategoryWhenAuthenticated()
    {
        $response = $this->actingAs($this->userWithActiveGroup(), 'api')->json('POST', '/api/v1/category', [
            'name' => '234234',
        ]);
        $response->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                ]
            ]);
    }

    public function testAddACategoryWhenUserHasNoActiveGroup()
    {
        $response = $this->actingAs($this->user(), 'api')->json('POST', '/api/v1/category', [
            'name' => '234234',
        ]);
        $response->assertStatus(422)
            ->assertExactJson([ "message" => "This user has no active group." ]);
    }

    public function testAddACategoryWhenNoInputGiven()
    {
        $response = $this->actingAs($this->userWithActiveGroup(), 'api')->json('POST', '/api/v1/category', []);
        $response->assertStatus(422)
            ->assertExactJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" => ["The name field is required."]
                ]
            ]);
    }
    public function testAddACategoryWhenInputIsInvalid()
    {
        $response = $this->actingAs($this->userWithActiveGroup(), 'api')->json('POST', '/api/v1/category', [
            'name' => $this->faker->text,
        ]);
        $response->assertStatus(422)
            ->assertExactJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" => ["The name may not be greater than 15 characters."]
                ]
            ]);
    }
}
