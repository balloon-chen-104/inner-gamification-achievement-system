<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiGroupTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCreatingANewGroupWhenNotAuthenticated()
    {
        $response = $this->json('POST', '/api/v1/group', [
            'name' => 'abc',
            'description' => $this->faker->text(10)
        ]);
        $response->assertUnauthorized();
    }

    public function testCreatingANewGroupWhenAuthenticated()
    {
        $response = $this->actingAs($this->user(), 'api')->json('POST', '/api/v1/group', [
            'name' => 'abc',
            'description' => $this->faker->text(10)
        ]);
        $response->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'creator'
                ]
            ]);
    }

    public function testCreatingANewGroupWhenNoInputGiven()
    {
        $response = $this->actingAs($this->user(), 'api')->json('POST', '/api/v1/group', []);
        $response->assertStatus(422)
            ->assertExactJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "description" => ["The description field is required."],
                    "name" => ["The name field is required."]
                ]
            ]);
    }

    public function testEnteringAGroupWhenNotAuthenticated()
    {
        $response = $this->json('POST', '/api/v1/group/enter', [
            'group_token' => $this->groupHarbor()->group_token,
        ]);
        $response->assertUnauthorized();
    }

    public function testEnteringAGroupWhenAuthenticated()
    {
        $response = $this->actingAs($this->user(), 'api')->json('POST', '/api/v1/group/enter', [
            'group_token' => $this->groupHarbor()->group_token,
        ]);
        $response->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'users' => [
                        '*' => [
                            "id",
                            "name",
                            "job_title",
                            "department",
                            "office_location",
                            "extension"
                        ]
                    ]
                ]
            ]);
    }
    public function testEnteringAGroupWhenNoInputGiven()
    {
        $response = $this->actingAs($this->user(), 'api')->json('POST', '/api/v1/group/enter', []);
        $response->assertStatus(422)
            ->assertExactJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "group_token" => [
                        "The group token field is required."
                    ]
                ]
            ]);
     }

    public function testEnteringAGroupWhenInputIsInvalid()
    {
        $response = $this->actingAs($this->user(), 'api')->json('POST', '/api/v1/group/enter', [
            'group_token' => '123456'
        ]);
        $response->assertStatus(422)
            ->assertExactJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "group_token" => [
                        "The group token must be 5 characters."
                    ]
                ]
            ]);
    }
    public function testEnteringAGroupWhenGroupTokenCannotFindAMatchGroup()
    {
        $response = $this->actingAs($this->user(), 'api')->json('POST', '/api/v1/group/enter', [
            'group_token' => '12345'
        ]);
        $response->assertStatus(422)
            ->assertExactJson([ "message" => "這個 group ID 找不到符合的群組" ]);
    }
}
