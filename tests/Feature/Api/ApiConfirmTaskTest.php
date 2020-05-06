<?php

namespace Tests\Feature\Api;

use App\Category;
use App\Group;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConfirmTaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testConfirmedTaskWhenNotAuthenticated()
    {
        $response = $this->json('POST', '/api/v1/task/confirmed', [
            'user_id' => $this->userWithActiveGroup()->id,
            'group_id' => Group::first()->id,
        ]);
        $response->assertUnauthorized();
    }

    public function testConfirmedTaskWhenAuthenticated()
    {
        // arrange: user enters the group
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
                'confirmed' => 1,
                'report' => 'ABC',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        });

        // action
        $response = $this->actingAs(User::first(), 'api')->json('POST', '/api/v1/task/confirmed', [
            'user_id' => User::first()->id,
            'group_id' => Group::first()->id,
        ]);

        // assert
        $response->assertSuccessful()
        ->assertJsonStructure([
            'data' =>[
                '*' => [
                    'id',
                    'name',
                    'description',
                    'score',
                    'remain_times',
                    'expired_at',
                    'category' => [
                        'id',
                        'name'
                    ],
                    'confirmed_at'
                ]
            ]
        ])
        ->assertJsonCount(1, 'data');
    }

    public function testConfirmedTaskWhenNoTasksFound()
    {
        $this->userWithActiveGroup();
        Group::first()->users()->attach(User::first()->id, ['authority' => 0]);

        $response = $this->actingAs(User::first(), 'api')->json('POST', '/api/v1/task/confirmed', [
            'user_id' => User::first()->id,
            'group_id' => Group::first()->id,
        ]);

        $response->assertSuccessful()
            ->assertJsonCount(0, 'data');

    }

    public function testConfirmedTaskWhen10TasksFound()
    {
        // arrange: user enters the group
        $this->userWithActiveGroup();
        Group::first()->users()->attach(User::first()->id, ['authority' => 0]);

        $this->userWithActiveGroup();
        Group::first()->users()->attach(User::first()->id, ['authority' => 1]);
        factory(Category::class)->create([
            'group_id' => Group::first()->id
        ]);

        // This user report the task
        factory(Task::class, 10)->create([
            'category_id' => Category::first()->id,
            'creator_id' => 1
        ])->each(function(Task $task) {
            $task->users()->attach(1, [
                'confirmed' => 1,
                'report' => 'ABC',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        });

        // action
        $response = $this->actingAs(User::first(), 'api')->json('POST', '/api/v1/task/confirmed', [
            'user_id' => User::first()->id,
            'group_id' => Group::first()->id,
        ]);

        // assert
        $response->assertSuccessful()
            ->assertJsonStructure([
                'data' =>[
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'score',
                        'remain_times',
                        'expired_at',
                        'category' => [
                            'id',
                            'name'
                        ],
                        'confirmed_at'
                    ]
                ]
            ])
            ->assertJsonCount(10, 'data');
    }

    public function testConfirmedTaskWhenUserIsNotFound()
    {
        $this->userWithActiveGroup();

        $response = $this->actingAs(User::first(), 'api')->json('POST', '/api/v1/task/confirmed', [
            'user_id' => 400,
            'group_id' => Group::first()->id,
        ]);

        $response->assertStatus(422)
            ->assertExactJson(['message' => 'The user is not found.']);
    }

    public function testConfirmedTaskWhenGroupIsNotFound()
    {
        $response = $this->actingAs($this->userWithActiveGroup(), 'api')->json('POST', '/api/v1/task/confirmed', [
            'user_id' => 1,
            'group_id' => 100
        ]);

        $response->assertStatus(422)
            ->assertExactJson(['message' => 'The group is not found.']);
    }

    public function testConfirmedTaskWhenUserIsNotInTheGivenGroup()
    {
        // arrange: user enters the group
        $this->userWithActiveGroup();
        Group::first()->users()->attach(User::first()->id, ['authority' => 0]);
        factory(Category::class)->create([
            'group_id' => Group::first()->id
        ]);

        $response = $this->actingAs(User::first(), 'api')->json('POST', '/api/v1/task/confirmed', [
            'user_id' => User::first()->id,
            'group_id' => $this->groupHarbor()->id,
        ]);

        $response->assertStatus(422)
            ->assertExactJson(['message' => 'The user is not in this group.']);
    }

    public function testConfirmedTaskWhenUserHasNoActiveGroup()
    {
        $response = $this->actingAs($this->user(), 'api')->json('POST', '/api/v1/task/confirmed', [
            'user_id' => $this->user()->id,
            'group_id' => $this->groupHarbor()->id,
        ]);

        $response->assertStatus(422)
            ->assertExactJson(['message' => 'The user has no active group.']);
    }

    public function testConfirmedTaskWhenNoInputIsGiven()
    {
        $response = $this->actingAs($this->userWithActiveGroup(), 'api')->json('POST', '/api/v1/task/confirmed', []);
        $response->assertStatus(422)
        ->assertExactJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'user_id' => ['The user id field is required.'],
                'group_id' => ['The group id field is required.']
            ]
        ]);
    }
}
