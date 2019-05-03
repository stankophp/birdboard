<?php

namespace Tests\Unit;

use App\Project;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_path()
    {
        $this->withExceptionHandling();

        /** @var $project Project */
        $project = factory(Project::class)->create();

        $this->assertEquals('/projects/'.$project->id, $project->path());
    }

    /** @test */
    public function it_has_an_owner()
    {
        $this->withExceptionHandling();

        /** @var $project Project */
        $project = factory(Project::class)->create();

        $this->assertInstanceOf(User::class, $project->owner);
    }

    /** @test */
    public function it_can_add_a_task()
    {
        $this->withExceptionHandling();

        /** @var $project Project */
        $project = factory(Project::class)->create();
        $task = $project->addTask('New Task');

        $this->assertCount(1, $project->tasks);
        $this->assertTrue($project->tasks->contains($task));
    }

    /** @test */
    public function it_can_invite_a_user()
    {
        $this->withExceptionHandling();

        /** @var $user User */
        $user = factory(User::class)->create();

        /** @var $project Project */
        $project = factory(Project::class)->create();
        $project->invite($user);

        $this->assertTrue($project->members->contains($user));
    }
}
