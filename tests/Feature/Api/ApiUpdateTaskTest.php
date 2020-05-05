<?php

namespace Tests\Feature\Api;

use App\Category;
use App\Group;
use App\Task;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiUpdateTaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testUpdateTaskWhenNotAuthenticated()
    {
        $response = $this->json('PUT', '/api/v1/task/'. $this->taskHarbor()->id, [
            'name' => 'Test 1',
            'description' => $this->faker->text(100),
            'category_id' => $this->categoryHarbor()->id,
            'expired_at' => Carbon::now()->addDays(3)->toDateString(),
            'score' => 100,
            'remain_times' => 10,
            'confirmed' => 0
        ]);
        $response->assertUnauthorized();
    }

    public function testUpdateTaskWhenAuthenticated()
    {
        $response = $this->actingAs($this->userWithActiveGroup(), 'api')->json('PUT', '/api/v1/task/'. $this->taskHarbor()->id, [
            'name' => 'Test 1',
            'description' => 'test description',
            'category_id' => $this->categoryHarbor()->id,
            'expired_at' => Carbon::now()->addDays(3)->toDateString(),
            'score' => 100,
            'remain_times' => 10,
            'confirmed' => 0
        ]);
        $response->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'score',
                    'remain_times',
                    'expired_at',
                    'category',
                ]
            ]);
    }

    public function testUpdateTaskWhenTaskIsNotFound()
    {
        $response = $this->actingAs($this->userWithActiveGroup(), 'api')->json('PUT', '/api/v1/task/'. '200', [
            'name' => 'Test 1',
            'description' => 'test description',
            'category_id' => $this->categoryHarbor()->id,
            'expired_at' => Carbon::now()->addDays(3)->toDateString(),
            'score' => 100,
            'remain_times' => 10,
            'confirmed' => 0
        ]);
        $response->assertStatus(422)
            ->assertExactJson([ 'message' => 'The task is not found.' ]);
    }

    public function testUpdateTaskWhenCategoryNotBelongToCurrentGroup()
    {
        factory(Category::class)->create([
            'group_id' => 10
        ]);
        $response = $this->actingAs($this->userWithActiveGroup(), 'api')->json('PUT', '/api/v1/task/'. $this->taskHarbor()->id, [
            'name' => 'Test 1',
            'description' => 'test description',
            'category_id' => 1,
            'expired_at' => Carbon::now()->addDays(3)->toDateString(),
            'score' => 100,
            'remain_times' => 10,
            'confirmed' => 0
        ]);
        $response->assertStatus(422)
            ->assertExactJson([ 'message' => 'This category is not in the current group.']);
    }

    public function testUpdateTaskWhenCategoryIdHasNoMatchCategory()
    {
        $response = $this->actingAs($this->userWithActiveGroup(), 'api')->json('PUT', '/api/v1/task/'. $this->taskHarbor()->id, [
            'name' => 'Test 1',
            'description' => 'test description',
            'category_id' => 2000,
            'expired_at' => Carbon::now()->addDays(3)->toDateString(),
            'score' => 100,
            'remain_times' => 10,
            'confirmed' => 0
        ]);
        $response->assertStatus(422)
            ->assertExactJson([ 'message' => 'The category is not found.' ]);
    }

    public function testUpdateTaskWhenExpiredTimeIsEarlierThanTomorrow()
    {
        $response = $this->actingAs($this->userWithActiveGroup(), 'api')->json('PUT', '/api/v1/task/'. $this->taskHarbor()->id, [
            'name' => 'Test 1',
            'description' => 'test description',
            'category_id' => $this->categoryHarbor()->id,
            'expired_at' => Carbon::now()->toDateString(),
            'score' => 100,
            'remain_times' => 10,
            'confirmed' => 0
        ]);
        $response->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                "errors" => [
                    "expired_at" => ["The expired at must be a date after tomorrow."]
                ]
            ]);
    }

    public function testUpdateTaskWhenRemainTimesIsLessThan1()
    {
        $response = $this->actingAs($this->userWithActiveGroup(), 'api')->json('PUT', '/api/v1/task/'. $this->taskHarbor()->id, [
            'name' => 'Test 1',
            'description' => 'test description',
            'category_id' => $this->categoryHarbor()->id,
            'expired_at' => Carbon::now()->addDays(2)->toDateString(),
            'score' => 100,
            'remain_times' => -1,
            'confirmed' => 0
        ]);
        $response->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                "errors" => [
                    "remain_times" => ["The remain times must be at least 1."]
                ]
            ]);
    }

    public function testUpdateTaskWhenScoreIsLessThan1()
    {
        $response = $this->actingAs($this->userWithActiveGroup(), 'api')->json('PUT', '/api/v1/task/'. $this->taskHarbor()->id, [
            'name' => 'Test 1',
            'description' => 'test description',
            'category_id' => $this->categoryHarbor()->id,
            'expired_at' => Carbon::now()->addDays(2)->toDateString(),
            'score' => -1,
            'remain_times' => 10,
            'confirmed' => 0
        ]);
        $response->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                "errors" => [
                    "score" => ["The score must be at least 1."]
                ]
            ]);
    }

    public function testUpdateTaskWhenTaskIsNotInTheCurrentGroup()
    {
        // create 2 groups; attach the category to group 2; attach the task to the category
        factory(Group::class, 2)->create([
            'creator_id' => 1
        ]);
        factory(Category::class)->create([
            'group_id' => 2
        ]);
        factory(Task::class)->create([
            'category_id' => Category::first()->id,
            'creator_id' => $this->user()->id
        ]);

        // The user is in group 1, where the task is not.
        $response = $this->actingAs($this->userWithActiveGroup(), 'api')->json('PUT', '/api/v1/task/'. Task::first()->id, [
            'name' => 'Test 1',
            'description' => 'test description',
            'category_id' => $this->categoryHarbor()->id,
            'expired_at' => Carbon::now()->addDays(2)->toDateString(),
            'score' => 100,
            'remain_times' => 10,
            'confirmed' => 0
        ]);

        // The assertion
        $response->assertStatus(422)
            ->assertExactJson(['message' => 'The task is not found in this group.']);

    }

    public function testUpdateTaskWhenUserHasNoActiveGroup()
    {
        $response = $this->actingAs($this->user(), 'api')->json('PUT', '/api/v1/task/'. $this->taskHarbor()->id, [
            'name' => 'Test 1',
            'description' => 'test description',
            'category_id' => $this->categoryHarbor()->id,
            'expired_at' => Carbon::now()->addDays(3)->toDateString(),
            'score' => 100,
            'remain_times' => 10,
            'confirmed' => 0
        ]);
        $response->assertStatus(422)
        ->assertExactJson([
            'message' => 'The user has no active group.'
        ]);
    }
}
