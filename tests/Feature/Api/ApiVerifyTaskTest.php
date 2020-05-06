<?php

namespace Tests\Feature\Api;

use App\Group;
use App\User;
use App\Category;
use App\Task;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiVerifyTaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testVerifyTaskWhenNotAuthenticated()
    {
        $response = $this->json('POST', '/api/v1/task/verify', [
            'task_id' => $this->taskHarbor()->id,
            'user_id' => $this->userWithActiveGroup()->id,
            'confirmed' => 1
        ]);
        $response->assertUnauthorized();
    }
    public function testVerifyTaskWhenNotAuthorized()
    {
        // arrange: user, who is not admin, enters the group
        $this->userWithActiveGroup();
        Group::first()->users()->attach(User::first()->id, ['authority' => 0]);

        // action
        $response = $this->actingAs(User::first(), 'api')->json('POST', '/api/v1/task/verify', [
            'task_id' => $this->taskHarbor()->id,
            'user_id' => $this->userWithActiveGroup()->id,
            'confirmed' => 1
        ]);

        // assert
        $response->assertStatus(422)
        ->assertExactJson([
            'message' => 'The user is not authorized.'
        ]);
    }
    public function testVerifyTaskWhenAuthorized()
    {
        // arrange: user, who is admin, enters the group
        $this->userWithActiveGroup();
        Group::first()->users()->attach(User::first()->id, ['authority' => 1]);
        factory(Category::class)->create([
            'group_id' => Group::first()->id
        ]);

        // This user report the task
        factory(Task::class)->create([
            'category_id' => Category::first()->id,
            'creator_id' => 1
        ])->each(function(Task $task) {
            $task->users()->attach(1, [
                'confirmed' => 0,
                'report' => 'ABC',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        });

        // action
        $response = $this->actingAs(User::first(), 'api')->json('POST', '/api/v1/task/verify', [
            'task_id' => Task::first()->id,
            'user_id' => User::first()->id,
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
                    'category' => [
                        'id',
                        'name'
                    ]
                ]
            ]);
    }
    public function testVerifyTaskWhenTaskNotFound()
    {
        $this->userWithActiveGroup();
        Group::first()->users()->attach(User::first()->id, ['authority' => 1]);

        $response = $this->actingAs(User::first(), 'api')->json('POST', '/api/v1/task/verify', [
            'task_id' => 100,
            'user_id' => $this->userWithActiveGroup()->id,
            'confirmed' => 1
        ]);
        $response->assertStatus(422)
        ->assertExactJson([
            'message' => 'The task is not found.'
        ]);
    }

    public function testVerifyTaskWhenUserHasNoActiveGroup()
    {
        $response = $this->actingAs($this->user(), 'api')->json('POST', '/api/v1/task/verify', [
            'task_id' => 100,
            'user_id' => $this->userWithActiveGroup()->id,
            'confirmed' => 1
        ]);
        $response->assertStatus(422)
        ->assertExactJson([
            'message' => 'The user has no active group.'
        ]);
    }

    public function testVerifyTaskWhenUserDoesNotReportThisTask()
    {
        // arrange: user, who is admin, enters the group
        $this->userWithActiveGroup();
        Group::first()->users()->attach(User::first()->id, ['authority' => 1]);
        factory(Category::class)->create([
            'group_id' => Group::first()->id
        ]);

        // This user report the task
        factory(Task::class)->create([
            'category_id' => Category::first()->id,
            'creator_id' => 1
        ])->each(function(Task $task) {
            $task->users()->attach($this->user(), [
                'confirmed' => 0,
                'report' => 'ABC',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        });

        // action
        $response = $this->actingAs(User::first(), 'api')->json('POST', '/api/v1/task/verify', [
            'task_id' => Task::first()->id,
            'user_id' => User::first()->id,
            'confirmed' => 1
        ]);

        // assert
        $response->assertStatus(422)
            ->assertExactJson([
                'message' => 'The task is not reported by this user.'
            ]);
    }

    public function testVerifyTaskWhenNoInputIsGiven()
    {
        $this->userWithActiveGroup();
        Group::first()->users()->attach(User::first()->id, ['authority' => 1]);
        $response = $this->actingAs(User::first(), 'api')->json('POST', '/api/v1/task/verify', []);

        $response->assertStatus(422)
        ->assertExactJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'confirmed' => ['The confirmed field is required.'],
                'user_id' => ['The user id field is required.'],
                'task_id' => ['The task id field is required.']
            ]
        ]);
    }

    public function testVerifyTaskWhenConfirmedIsInvalid()
    {
        // arrange: user, who is admin, enters the group
        $this->userWithActiveGroup();
        Group::first()->users()->attach(User::first()->id, ['authority' => 1]);
        factory(Category::class)->create([
            'group_id' => Group::first()->id
        ]);

        // This user report the task
        factory(Task::class)->create([
            'category_id' => Category::first()->id,
            'creator_id' => 1
        ])->each(function(Task $task) {
            $task->users()->attach(1, [
                'confirmed' => 0,
                'report' => 'ABC',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        });

        // action
        $response = $this->actingAs(User::first(), 'api')->json('POST', '/api/v1/task/verify', [
            'task_id' => Task::first()->id,
            'user_id' => User::first()->id,
            'confirmed' => 66
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
