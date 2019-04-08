<?php

namespace Tests\Unit;

use App\Project;
use App\Task;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_path()
    {
        $this->withExceptionHandling();

        /** @var $project Project */
        $project = factory('App\Project')->create();
        /** @var $task Task */
        $task = $project->addTask('Some body');

        $this->assertEquals('/projects/'.$task->project_id.'/tasks/'.$task->id, $task->path());
    }

    /** @test */
//    public function it_has_an_owner()
//    {
//        $this->withExceptionHandling();
//
//        /** @var $project Project */
//        $project = factory('App\Project')->create();
//
//        $this->assertInstanceOf('App\User', $project->owner);
//    }
}
