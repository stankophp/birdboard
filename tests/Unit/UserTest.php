<?php

namespace Tests\Unit;

use App\Project;
use App\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_has_projects()
    {
        $user = factory(User::class)->create();
        /** @var $user User */
        $this->assertInstanceOf(HasMany::class, $user->projects());
    }

    /** @test */
    public function a_user_has_all_projects()
    {
        /** @var $john User */
        $john = factory(User::class)->create();

        /** @var $project Project */
        $project = ProjectFactory::ownedBy($john)->create();
        
        $this->assertCount(1, $john->allProjects());

        /** @var User $bob */
        $bob = factory(User::class)->create();
        /** @var User $nick */
        $nick = factory(User::class)->create();
        $project->invite($nick);

        $this->assertCount(0, $bob->allProjects());
        $this->assertCount(1, $john->allProjects());
        $this->assertCount(1, $nick->allProjects());
    }
}
