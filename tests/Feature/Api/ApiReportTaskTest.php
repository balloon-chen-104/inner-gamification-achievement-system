<?php

namespace Tests\Feature\Api;

use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiReportTaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testReportTaskWhenNotAuthenticated()
    {
        $response = $this->json('POST', '/api/v1/task/report', [
            'id' => $this->taskHarbor()->id,
            'report' => 'Test report'
        ]);
        $response->assertUnauthorized();
    }

    public function testReportTaskWhenAuthenticated()
    {
        $response = $this->actingAs($this->user(), 'api')->json('POST', '/api/v1/task/report', [
            'id' => $this->taskHarbor()->id,
            'report' => 'Test report'
        ]);
        $response->assertSuccessful()
            ->assertJsonStructure([
                'task_id', 'user_id', 'confirmed', 'report', 'created_at', 'updated_at'
            ]);
    }

    public function testReportTaskWhenTaskIsNotFound()
    {
        $response = $this->actingAs($this->user(), 'api')->json('POST', '/api/v1/task/report', [
            'id' => 500,
            'report' => 'Test report'
        ]);
        $response->assertStatus(422)
            ->assertExactJson([ 'message' => 'The task is not found.' ]);
    }

    public function testReportTaskWhenNoTaskIdIsGiven()
    {
        $response = $this->actingAs($this->user(), 'api')->json('POST', '/api/v1/task/report', []);
        $response->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "id" =>["The id field is required."]
                ],
                "message" => "The given data was invalid."
            ]);
    }
}
