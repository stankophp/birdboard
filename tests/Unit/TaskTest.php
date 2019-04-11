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
    public function it_belongs_to_a_project()
    {
        /** @var $task Task */
        $task = factory('App\Task')->create();

        $this->assertInstanceOf(Project::class, $task->project);
    }

    /** @test */
    public function it_has_a_path()
    {
        /** @var $project Project */
        $project = factory('App\Project')->create();
        /** @var $task Task */
        $task = $project->addTask('Some body');

        $this->assertEquals('/projects/'.$task->project->id.'/tasks/'.$task->id, $task->path());
    }

    /** @test */
    public function it_can_be_completed()
    {
        /** @var $project Project */
        $project = factory('App\Project')->create();
        /** @var $task Task */
        $task = $project->addTask('Some body');

        $this->assertFalse($task->fresh()->completed);
        $task->complete();

        $this->assertTrue($task->fresh()->completed);
    }

    /** @test */
    public function it_can_be_incompleted()
    {
        $this->withExceptionHandling();

        /** @var $project Project */
        $project = factory('App\Project')->create();
        /** @var $task Task */
        $task = $project->addTask('Some body');

        $task->complete();
        $this->assertTrue($task->fresh()->completed);

        $task->incomplete();
        $this->assertFalse($task->fresh()->completed);
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
