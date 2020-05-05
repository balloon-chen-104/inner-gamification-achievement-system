<?php

namespace Tests\Feature\Api;

use App\Category;
use App\Group;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiAddNewTaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testAddNewTaskWhenNotAuthenticated()
    {
        $response = $this->json('POST', '/api/v1/task', [
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

    public function testAddNewTaskWhenAuthenticated()
    {
        $response = $this->actingAs($this->userWithActiveGroup(), 'api')->json('POST', '/api/v1/task', [
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

    public function testAddNewTaskWhenCategoryIdHasNoMatchCategory()
    {
        $response = $this->actingAs($this->userWithActiveGroup(), 'api')->json('POST', '/api/v1/task', [
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

    public function testAddNewTaskWhenCategoryNotBelongToCurrentGroup()
    {
        factory(Category::class)->create([
            'group_id' => 10
        ]);
        $response = $this->actingAs($this->userWithActiveGroup(), 'api')->json('POST', '/api/v1/task', [
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

    public function testAddNewTaskWhenExpiredTimeIsEarlierThanTomorrow()
    {
        $response = $this->actingAs($this->userWithActiveGroup(), 'api')->json('POST', '/api/v1/task', [
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

    public function testAddNewTaskWhenRemainTimesIsLessThan1()
    {
        $response = $this->actingAs($this->userWithActiveGroup(), 'api')->json('POST', '/api/v1/task', [
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

    public function testAddNewTaskWhenScoreIsLessThan1()
    {
        $response = $this->actingAs($this->userWithActiveGroup(), 'api')->json('POST', '/api/v1/task', [
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

    public function testAddNewTaskWhenConfirmedIsInvalid()
    {
        $response = $this->actingAs($this->userWithActiveGroup(), 'api')->json('POST', '/api/v1/task', [
            'name' => 'Test 1',
            'description' => 'test description',
            'category_id' => $this->categoryHarbor()->id,
            'expired_at' => Carbon::now()->addDays(2)->toDateString(),
            'score' => 100,
            'remain_times' => 10,
            'confirmed' => -5
        ]);
        $response->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                "errors" => [
                    "confirmed" => ["The confirmed must be between -1 and 1."]
                ]
            ]);
    }
    public function testAddNewTaskWhenUserHasNoActiveGroup()
    {
        $response = $this->actingAs($this->user(), 'api')->json('POST', '/api/v1/task', [
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
