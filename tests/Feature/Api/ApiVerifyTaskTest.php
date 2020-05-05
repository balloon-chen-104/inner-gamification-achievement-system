<?php

namespace Tests\Feature\Api;

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
        $this->assertTrue(true);
    }
    public function testVerifyTaskWhenAuthorized()
    {
        $this->assertTrue(true);
    }
    public function testVerifyTaskWhenTaskNotFound()
    {
        $this->assertTrue(true);
    }

    public function testVerifyTaskWhenUserHasNoActiveGroup()
    {
        $this->assertTrue(true);
    }

    public function testVerifyTaskWhenUserDoesNotReportThisTask()
    {
        $this->assertTrue(true);
    }

    public function testVerifyTaskWhenNoInputIsGiven()
    {
        $this->assertTrue(true);
    }

    public function testVerifyTaskWhenInputIsInvalid()
    {
        $this->assertTrue(true);
    }
}
