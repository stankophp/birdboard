<?php

namespace Tests\Feature;

use App\Project;
use App\User;
use Tests\Setup\ProjectFactory;
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
        $user = $this->signIn();

        $this->get('/projects/create')->assertStatus(SymfonyResponse::HTTP_OK);

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'notes' => $this->faker->sentence,
        ];
        $attributes['owner_id'] = $user->id;

        $this->actingAs($user)
            ->followingRedirects()
            ->post('/projects', $attributes)
            ->assertSee($attributes['title'])
            ->assertSee(substr($attributes['description'], 0, 150))
            ->assertSee($attributes['notes']);

        $this->assertDatabaseHas('projects', $attributes);
    }

    public function a_user_can_view_own_project()
    {
        $user = $this->signIn();

        /** @var $project Project */
        $project = app(ProjectFactory::class)->create();

        $this->actingAs($user)
            ->get($project->path())
            ->assertSee($project->title)
            ->assertSee(substr($project->description, 0, 150))
            ->assertSee($project->notes);
    }

    /** @test */
    public function a_user_can_delete_a_project()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $this->signIn($user);

        /** @var $project Project */
        $project = app(ProjectFactory::class)->create();

        $this->actingAs($project->owner)
            ->delete($project->path())
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    /** @test */
    public function a_unauthorised_user_cant_delete_a_project()
    {
        /** @var $project Project */
        $project = app(ProjectFactory::class)->create();

        $this->delete($project->path())
            ->assertRedirect('/login');

        $user = factory(User::class)->create();
        $this->signIn($user);

        $this->delete($project->path())
            ->assertStatus(SymfonyResponse::HTTP_FORBIDDEN);

        $project->invite($user);

        $this->actingAs($user)
            ->delete($project->path())
            ->assertStatus(SymfonyResponse::HTTP_FORBIDDEN);
    }

    /** @test */
    public function a_user_can_see_all_projects_they_are_invited_on_dashboard()
    {
        $user = $this->signIn();

        /** @var $project Project */
        $project = app(ProjectFactory::class)->create();
        $project->invite($user);

        $this->get('/projects')->assertSee($project->title);
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
//        $this->withoutExceptionHandling();

        /** @var $project Project */
        $project = app(ProjectFactory::class)->create();

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'notes' => $this->faker->sentence,
        ];

        $this->actingAs($project->owner)
            ->patch($project->path(), $attributes)
            ->assertRedirect($project->path());

        $this->get($project->path().'/edit')->assertOk();

        $this->assertDatabaseHas('projects', $attributes);
        $this->get($project->path())
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_user_can_update_project_general_notes()
    {
        /** @var $project Project */
        $project = app(ProjectFactory::class)->create();

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'notes' => $this->faker->sentence,
        ];

        $this->actingAs($project->owner)
            ->patch($project->path(), $attributes)
            ->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);
        $this->get($project->path())
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_project_needs_title()
    {
        $user = factory(User::class)->create();
        $this->signIn($user);

        $attributes = factory(Project::class)->raw(['title' => null, 'owner_id' => $user->id]);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_needs_description()
    {
        $user = factory(User::class)->create();
        $this->signIn($user);

        $attributes = factory(Project::class)->raw(['description' => null, 'owner_id' => $user->id]);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

    /** @test */
    public function guest_cant_manage_projects()
    {
        // index
        $this->get('/projects')->assertRedirect('login');

        // store
        $attributes = factory(Project::class)->raw();
        $this->post('/projects', $attributes)->assertRedirect('login');

        // create
        $this->get('/projects/create')->assertRedirect('login');

        // view
        /** @var $project Project */
        $project = factory(Project::class)->create();
        $this->get($project->path())->assertRedirect('login');
        $this->get($project->path().'/edit')->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_view_a_project()
    {
        /** @var $project Project */
        $project = app(ProjectFactory::class)->create();

        $this->actingAs($project->owner)
            ->get($project->path())
            ->assertSee($project->title)
            ->assertSee(substr($project->description, 0, 150));
    }

    /** @test */
    public function a_user_cant_view_projects_of_other_users()
    {
        $this->signIn();

        /** @var $project Project */
        $project = factory(Project::class)->create();

        $this->get($project->path())
            ->assertStatus(SymfonyResponse::HTTP_FORBIDDEN);
    }

    /** @test */
    public function a_user_cant_edit_projects_of_other_users()
    {
        $this->signIn();

        /** @var $project Project */
        $project = factory(Project::class)->create();

        $this->patch($project->path(), ['notes' => $this->faker->sentence,])
            ->assertStatus(SymfonyResponse::HTTP_FORBIDDEN);
    }
}
