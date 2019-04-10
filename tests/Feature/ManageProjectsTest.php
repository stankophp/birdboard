<?php

namespace Tests\Feature;

use App\Project;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_project()
    {
        $user = factory(User::class)->create();
        $this->signIn($user);
        $this->withoutExceptionHandling();

        $this->get('/projects/create')->assertStatus(SymfonyResponse::HTTP_OK);

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'notes' => $this->faker->sentence,
        ];
        $attributes['owner_id'] = $user->id;

        $response = $this->post('/projects', $attributes);
        /** @var Project $project */
        $project = Project::where($attributes)->first();
        $response->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);

        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee(substr($attributes['description'], 0, 150))
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
        $user = factory(User::class)->create();
        $this->signIn($user);
        $this->withoutExceptionHandling();

        /** @var $project Project */
        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $attributes = [
            'notes' => $this->faker->sentence,
        ];

        $this->patch($project->path(), $attributes)->assertRedirect($project->path());
        $this->assertDatabaseHas('projects', $attributes);
        $this->get($project->path())->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_project_needs_title()
    {
        $this->signIn();

        $attributes = factory('App\Project')->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_needs_description()
    {
        $this->signIn();

        $attributes = factory('App\Project')->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

    /** @test */
    public function guest_cant_manage_projects()
    {
        // index
        $this->get('/projects')->assertRedirect('login');

        // store
        $attributes = factory('App\Project')->raw();
        $this->post('/projects', $attributes)->assertRedirect('login');

        // create
        $this->get('/projects/create')->assertRedirect('login');

        // view
        /** @var $project Project */
        $project = factory('App\Project')->create();
        $this->get($project->path())->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_view_a_project()
    {
        $this->signIn();

        $this->withoutExceptionHandling();

        /** @var $project Project */
        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee(substr($project->description, 0, 150));
    }

    /** @test */
    public function a_user_cant_view_projects_of_other_users()
    {
        $this->signIn();

        /** @var $project Project */
        $project = factory('App\Project')->create();

        $this->get($project->path())
            ->assertStatus(SymfonyResponse::HTTP_FORBIDDEN);
    }

    /** @test */
    public function a_user_cant_edit_projects_of_other_users()
    {
        $this->signIn();

        /** @var $project Project */
        $project = factory('App\Project')->create();

        $this->patch($project->path(), ['notes' => $this->faker->sentence,])
            ->assertStatus(SymfonyResponse::HTTP_FORBIDDEN);
    }
}
