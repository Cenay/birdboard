<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\App;
use Facades\Tests\Setup\ProjectTestFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_has_projects()
    {
        $user = factory('App\User')->create();
        $this->assertInstanceOf(Collection::class, $user->projects);
    }

    /** @test */
    public function a_user_has_accessible_projects()
    {
        $john = $this->signIn();
        ProjectTestFactory::ownedBy($john)->create();

        $this->assertCount(1, $john->accessibleProjects());

        $sally = factory(User::class)->create();
        $nick = factory(User::class)->create();

        $project = tap(ProjectTestFactory::ownedBy($sally)->create())->invite($nick);
        // Sanity check, that shouldn't have affect John.
        $this->assertCount(1, $john->accessibleProjects());

        $project->invite($john);

        // NOW it should be two
        $this->assertCount(2, $john->accessibleProjects());
    }
}
