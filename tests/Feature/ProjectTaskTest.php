<?php

namespace Tests\Feature;

use App\Project;
use App\Task;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ProjectTaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks()
    {
        $this->signIn();

        /** @var Project $project */
        $project = factory(Project::class)->create(['owner_id' => auth()->id()]);

        $body = 'A Project Can Have Tasks';
        $this->post($project->path() . '/tasks', ['body' => $body]);

        $this->get($project->path())
            ->assertSee($body);
    }

    /** @test */
    public function a_task_needs_body()
    {
        $this->signIn();

        /** @var Project $project */
        $project = factory(Project::class)->create(['owner_id' => auth()->id()]);
        $attributes = factory('App\Task')->raw(['body' => '']);

        $this->post($project->path().'/tasks', $attributes)->assertSessionHasErrors('body');
    }

    /** @test */
    public function only_an_owner_can_add_task_to_project()
    {
        $this->signIn();

        /** @var Project $project */
        $project = factory(Project::class)->create();
        $body = 'A Project Can Have Tasks';
        $attributes = factory('App\Task')->raw(['body' => $body]);

        $this->post($project->path().'/tasks', $attributes)
            ->assertStatus(SymfonyResponse::HTTP_FORBIDDEN);

        $this->assertDatabaseMissing('tasks', $attributes);
    }

    /** @test */
    public function only_an_owner_can_update_a_task()
    {
        $this->signIn();

        /** @var Project $project */
        $project = factory(Project::class)->create();
        $body = 'A Project Can Have Tasks';
        /** @var Task $task */
        $task = $project->addTask($body);

        $attributes = ['body' => 'Updated', 'completed' => true];

        $this->patch($task->path(), $attributes)
            ->assertStatus(SymfonyResponse::HTTP_FORBIDDEN);

        $this->assertDatabaseMissing('tasks', $attributes);
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        $this->signIn();

        /** @var User $user */
        $user = auth()->user();

        /** @var Project $project */
        $project = $user->projects()->create(factory(Project::class)->raw());
        /** @var Task $task */
        $task = $project->addTask('attributes factory');

        $attributes = ['body' => 'Updated', 'completed' => true];

        $this->patch($task->path(), $attributes);

        $this->assertDatabaseHas('tasks', $attributes);
    }
}
