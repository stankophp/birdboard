<?php

namespace Tests\Feature;

use App\Project;
use App\User;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class InvitationsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_project_can_invite_a_user()
    {
        /** @var $user User */
        $user = factory(User::class)->create();

        /** @var $project Project */
        $project = app(ProjectFactory::class)->create();

        $this->actingAs($project->owner)
            ->post($project->path().'/invitations', [
                'email' => $user->email
            ])
            ->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($user));
    }

    /** @test */
    public function a_email_must_be_for_registered_user()
    {
        /** @var $project Project */
        $project = app(ProjectFactory::class)->create();

        $this->actingAs($project->owner)
            ->post($project->path().'/invitations', [
                'email' => 'user_no_exist@email.com'
            ])
            ->assertSessionHasErrors(['email'], null, 'invitations');
//            ->assertStatus(SymfonyResponse::HTTP_NOT_FOUND);
    }

    /** @test */
    public function a_project_owner_only_can_invite_a_user()
    {
        /** @var $project Project */
        $project = app(ProjectFactory::class)->create();

        /** @var $user User */
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post($project->path().'/invitations', [
                'email' =>  $user->email
            ])
            ->assertStatus(SymfonyResponse::HTTP_FORBIDDEN);

        $project->invite($user);

        $this->actingAs($user)
            ->post($project->path().'/invitations', [
                'email' =>  $user->email
            ])
            ->assertStatus(SymfonyResponse::HTTP_FORBIDDEN);
    }

    /** @test */
    public function a_invited_user_can_edit_a_project()
    {
        /** @var $user User */
        $user = factory(User::class)->create();

        /** @var $project Project */
        $project = app(ProjectFactory::class)->create();
        $project->invite($user);

        $this->signIn($user);

        $this->post(action('ProjectTasksController@store', $project), $task = ['body' => 'Foo task']);

        $this->assertDatabaseHas('tasks', $task);
    }
}
