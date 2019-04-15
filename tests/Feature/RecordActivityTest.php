<?php

namespace Tests\Feature;

use App\Activity;
use App\Project;
use App\Task;
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

        tap($project->activity->last(), function ($activity) {
            /** @var Activity $activity */
            $this->assertEquals('project_created', $activity->description);

            $this->assertNull($activity->changes);
        });
    }

    /** @test */
    public function updating_a_project()
    {
        $this->withExceptionHandling();
        /** @var Project $project */
        $project = app(ProjectFactory::class)->create();
        $originalTitle = $project->title;
        $project->update(['title' => 'Updated']);

        $this->assertCount(2, $project->activity);
        tap($project->activity->last(), function ($activity) use ($originalTitle) {
            /** @var Activity $activity */
            $this->assertEquals('project_updated', $activity->description);

            $expected = [
                'before' => ['title' => $originalTitle],
                'after' => ['title' => 'Updated']
            ];

            $this->assertEquals($expected, $activity->changes);
        });
    }

    /** @test */
    public function creating_a_task()
    {
        $body = 'Some title';
        /** @var Project $project */
        $project = app(ProjectFactory::class)->create();
        $project->addTask($body);

        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function ($activity) {
            /** @var Activity $activity*/
            $this->assertEquals('task_created', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('Some title', $activity->subject->body);
        });
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
        $this->assertEquals('project_created', $project->activity[0]->description);
        $this->assertEquals('task_created', $project->activity[1]->description);
        $this->assertEquals('task_completed', $project->activity[2]->description);

        tap($project->activity->last(), function ($activity) {
            /** @var Activity $activity*/
            $this->assertEquals('task_completed', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('Updated', $activity->subject->body);
        });
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
        $this->assertEquals('project_created', $project->activity[0]->description);
        $this->assertEquals('task_created', $project->activity[1]->description);
        $this->assertEquals('task_completed', $project->activity[2]->description);

        $attributes = ['body' => 'Updated', 'completed' => false];

        $this->patch($project->tasks->first()->path(), $attributes);

        $project->refresh();
        $this->assertCount(4, $project->activity);
        $this->assertEquals('project_created', $project->activity[0]->description);
        $this->assertEquals('task_created', $project->activity[1]->description);
        $this->assertEquals('task_completed', $project->activity[2]->description);
        $this->assertEquals('task_incompleted', $project->activity[3]->description);
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
