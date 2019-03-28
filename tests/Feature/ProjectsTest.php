<?php

namespace Tests\Feature;

use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function a_user_can_create_a_project()
    {
        $user = factory('App\User')->create();
        $this->actingAs($user);
        $this->withoutExceptionHandling();

        $attributes = factory('App\Project')->raw();
        $attributes['owner_id'] = $user->id;

        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    /** @test */
    public function a_project_needs_title()
    {
        $this->actingAs(factory('App\User')->create());

        $attributes = factory('App\Project')->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_needs_description()
    {
        $this->actingAs(factory('App\User')->create());

        $attributes = factory('App\Project')->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

    /** @test */
//    public function a_project_needs_an_owner()
//    {
//        $this->actingAs(factory('App\User')->create());
//
//        $attributes = factory('App\Project')->raw();
//        $attributes['owner_id'] = null;
//
//        $this->post('/projects', $attributes)->assertSessionHasErrors('owner_id');
//    }

    /** @test */
    public function guest_cant_create_a_project()
    {
        $attributes = factory('App\Project')->raw();

        $this->post('/projects', $attributes)->assertRedirect('login');
    }

    /** @test */
    public function guest_cant_view_projects()
    {
        $this->get('/projects')->assertRedirect('login');
    }

    /** @test */
    public function guest_cant_view_single_project()
    {
        /** @var $project Project */
        $project = factory('App\Project')->create();
        $this->get($project->path())->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_view_a_project()
    {
        $user = factory('App\User')->create();
        $this->actingAs($user);

        $this->withoutExceptionHandling();

        /** @var $project Project */
        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function a_user_cant_view_projects_of_other_users()
    {
        $user = factory('App\User')->create();
        $this->actingAs($user);

//        $this->withoutExceptionHandling();

        /** @var $project Project */
        $project = factory('App\Project')->create();

        $this->get($project->path())
            ->assertStatus(SymfonyResponse::HTTP_FORBIDDEN);
    }
}
