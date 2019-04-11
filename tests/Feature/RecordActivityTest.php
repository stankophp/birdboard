<?php

namespace Tests\Feature;

use App\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class RecordActivityTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function creating_a_project()
    {
        /** @var Project $project */
        $project = app(ProjectFactory::class)->create();

        $this->assertCount(1, $project->activity);
        $this->assertEquals('created', $project->activity[0]->description);
    }

    /** @test */
    public function updating_a_project()
    {
        /** @var Project $project */
        $project = app(ProjectFactory::class)->create();
        $project->update(['title' => 'Updated']);

        $this->assertCount(2, $project->activity);
        $this->assertEquals('created', $project->activity[0]->description);
        $this->assertEquals('updated', $project->activity[1]->description);
    }

    /** @test */
    public function creating_a_task()
    {
        /** @var Project $project */
        $project = app(ProjectFactory::class)->create();
        $project->addTask('title');

        $this->assertCount(2, $project->activity);
        $this->assertEquals('created', $project->activity[0]->description);
        $this->assertEquals('created_task', $project->activity[1]->description);
    }

    /** @test */
    public function completing_a_task()
    {
        $this->signIn();

        /** @var Project $project */
        $project = app(ProjectFactory::class)
            ->withTasks(1)
            ->create();

        $attributes = ['body' => 'Updated', 'completed' => true];

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), $attributes);

        $this->assertCount(3, $project->activity);
        $this->assertEquals('created', $project->activity[0]->description);
        $this->assertEquals('created_task', $project->activity[1]->description);
        $this->assertEquals('completed_task', $project->activity[2]->description);
    }

    /** @test */
    public function incompleting_a_task()
    {
        $this->signIn();

        /** @var Project $project */
        $project = app(ProjectFactory::class)
            ->withTasks(1)
            ->create();

        $attributes = ['body' => 'Updated', 'completed' => true];

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), $attributes);

        $this->assertCount(3, $project->activity);
        $this->assertEquals('created', $project->activity[0]->description);
        $this->assertEquals('created_task', $project->activity[1]->description);
        $this->assertEquals('completed_task', $project->activity[2]->description);

        $attributes = ['body' => 'Updated', 'completed' => false];

        $this->patch($project->tasks->first()->path(), $attributes);

        $project->refresh();
        $this->assertCount(4, $project->activity);
        $this->assertEquals('created', $project->activity[0]->description);
        $this->assertEquals('created_task', $project->activity[1]->description);
        $this->assertEquals('completed_task', $project->activity[2]->description);
        $this->assertEquals('incompleted_task', $project->activity[3]->description);
    }

    /** @test */
    public function deleting_a_task()
    {
        $this->signIn();

        /** @var Project $project */
        $project = app(ProjectFactory::class)
            ->withTasks(1)
            ->create();

        $project->tasks->first()->delete();

        $this->assertCount(3, $project->activity);

        $project->refresh();
        $this->assertCount(0, $project->tasks);

    }
}
