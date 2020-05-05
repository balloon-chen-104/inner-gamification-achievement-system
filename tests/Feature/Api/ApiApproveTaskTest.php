<?php

namespace Tests\Feature\Api;

use App\Group;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiApproveTaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testApproveTaskWhenNotAuthenticated()
    {
        $response = $this->json('POST', '/api/v1/task/approve', [
            'id' => $this->taskHarbor()->id,
            'confirmed' => 1
        ]);
        $response->assertUnauthorized();
    }
    public function testApproveTaskWhenNotAuthorized()
    {
        // arrange: user, who is not admin, enters the group
        $this->userWithActiveGroup();
        Group::first()->users()->attach(User::first()->id, ['authority' => 0]);

        // action
        $response = $this->actingAs(User::first(), 'api')->json('POST', '/api/v1/task/approve', [
            'id' => $this->taskHarbor()->id,
            'confirmed' => 1
        ]);

        // assert
        $response->assertStatus(422)
        ->assertExactJson([
            'message' => 'The user is not authorized.'
        ]);
    }
    public function testApproveTaskWhenAuthorized()
    {
        // arrange: user, who is admin, enters the group
        $this->userWithActiveGroup();
        Group::first()->users()->attach(User::first()->id, ['authority' => 1]);

        // action
        $response = $this->actingAs(User::first(), 'api')->json('POST', '/api/v1/task/approve', [
            'id' => $this->taskHarbor()->id,
            'confirmed' => 1
        ]);

        // assert
        $response->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'score',
                    'remain_times',
                    'expired_at',
                    'category' => ['id', 'name'],
                ]
            ]);
    }
    public function testApproveTaskWhenTaskNotFound()
    {
        $this->userWithActiveGroup();
        Group::first()->users()->attach(User::first()->id, ['authority' => 1]);

        $response = $this->actingAs(User::first(), 'api')->json('POST', '/api/v1/task/approve', [
            'id' => 2000,
            'confirmed' => 1
        ]);
        $response->assertStatus(422)
        ->assertExactJson([
            'message' => 'The task is not found.'
        ]);
    }

    public function testApproveTaskWhenUserHasNoActiveGroup()
    {
        $response = $this->actingAs($this->user(), 'api')->json('POST', '/api/v1/task/approve', [
            'id' => $this->taskHarbor()->id,
            'confirmed' => 1
        ]);
        $response->assertStatus(422)
        ->assertExactJson([
            'message' => 'The user has no active group.'
        ]);
    }

    public function testApproveTaskWhenNoInputIsGiven()
    {
        $this->userWithActiveGroup();
        Group::first()->users()->attach(User::first()->id, ['authority' => 1]);
        $response = $this->actingAs(User::first(), 'api')->json('POST', '/api/v1/task/approve', []);

        $response->assertStatus(422)
        ->assertExactJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'confirmed' => ['The confirmed field is required.'],
                'id' => ['The id field is required.']
            ]
        ]);
    }

    public function testApproveTaskWhenConfirmedIsInvalid()
    {
        $response = $this->actingAs($this->user(), 'api')->json('POST', '/api/v1/task/approve', [
            'id' => $this->taskHarbor()->id,
            'confirmed' => 3
        ]);
        $response->assertStatus(422)
        ->assertExactJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'confirmed' => ['The confirmed must be between -1 and 1.'],
            ]
        ]);
    }
}
