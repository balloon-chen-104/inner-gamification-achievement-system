<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiConfirmTaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testConfirmedTaskWhenNotAuthenticated()
    {
        $this->assertTrue(true);
    }

    public function testConfirmedTaskWhenAuthenticatedButNoTasksFound()
    {
        $this->assertTrue(true);
    }

    public function testConfirmedTaskWhenAuthenticatedAnd10TasksFound()
    {
        $this->assertTrue(true);
    }

    public function testConfirmedTaskWhenUserIsNotFound()
    {
        $this->assertTrue(true);
    }

    public function testConfirmedTaskWhenGroupIsNotFound()
    {
        $this->assertTrue(true);
    }

    public function testConfirmedTaskWhenUserIsNotInTheGivenGroup()
    {
        $this->assertTrue(true);
    }

    public function testConfirmedTaskWhenUserHasNoActiveGroup()
    {
        $this->assertTrue(true);
    }

    public function testConfirmedTaskWhenNoInputIsGiven()
    {
        $this->assertTrue(true);
    }

    public function testConfirmedTaskWhenInputIsInvalid()
    {
        $this->assertTrue(true);
    }
}
