<?php

namespace Tests\Feature;

use App\Project;
use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ProjectTaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks()
    {
        /** @var Project $project */
        $project = app(ProjectFactory::class)->create();

        $body = 'A Project Can Have Tasks';
        $this->actingAs($project->owner)
            ->post($project->path() . '/tasks', ['body' => $body]);

        $this->get($project->path())
            ->assertSee($body);
    }

    /** @test */
    public function a_task_needs_body()
    {
        /** @var Project $project */
        $project = app(ProjectFactory::class)->create();

        $attributes = factory(Task::class)->raw(['body' => '']);

        $this->actingAs($project->owner)
            ->post($project->path().'/tasks', $attributes)
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function only_an_owner_can_add_task_to_project()
    {
        $this->signIn();

        $body = 'A Project Can Have Tasks';
        $attributes = ['body' => $body];

        /** @var Project $project */
        $project = app(ProjectFactory::class)
            ->withTasks(1)
            ->create();

        $this->post($project->path().'/tasks', $attributes)
            ->assertStatus(SymfonyResponse::HTTP_FORBIDDEN);

        $this->assertDatabaseMissing('tasks', $attributes);
    }

    /** @test */
    public function only_an_owner_can_update_a_task()
    {
        $this->signIn();

        /** @var Project $project */
        $project = app(ProjectFactory::class)
            ->withTasks(1)
            ->create();

        $attributes = ['body' => 'Updated', 'completed' => true];

        $this->patch($project->tasks->first()->path(), $attributes)
            ->assertStatus(SymfonyResponse::HTTP_FORBIDDEN);

        $this->assertDatabaseMissing('tasks', $attributes);
    }

    /** @test */
    public function a_task_body_can_be_updated()
    {
        /** @var Project $project */
        $project = app(ProjectFactory::class)
            ->withTasks(1)
            ->create();

        $attributes = ['body' => 'Updated'];

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), $attributes);

        $this->assertDatabaseHas('tasks', $attributes);
    }

    /** @test */
    public function a_task_can_be_completed()
    {
        /** @var Project $project */
        $project = app(ProjectFactory::class)
            ->withTasks(1)
            ->create();

        $attributes = ['body' => 'Updated', 'completed' => true];

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), $attributes);

        $this->assertDatabaseHas('tasks', $attributes);
    }

    /** @test */
    public function a_task_can_be_not_completed()
    {
        /** @var Project $project */
        $project = app(ProjectFactory::class)
            ->withTasks(1)
            ->create();

        $attributes = ['body' => 'Updated', 'completed' => true];

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), $attributes);

        $attributes = ['body' => 'Updated', 'completed' => false];

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), $attributes);

        $this->assertDatabaseHas('tasks', $attributes);
    }
}
