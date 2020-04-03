<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\App;
use Facades\Tests\Setup\ProjectTestFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_path()
    {
        $project = factory('App\Project')->create();
        $this->assertEquals('/projects/' . $project->id, $project->path());

    }

	/** @test */
    public function it_belongs_to_an_owner()
    {
        $project = factory('App\Project')->create();
        $this->assertInstanceOf('App\User', $project->owner);
    }

	/** @test */
    public function it_can_add_a_task()
    {
        $project = factory('App\Project')->create();
        $task = $project->addTask('Test task');
		
        $this->assertCount(1, $project->tasks);
        $this->assertTrue($project->tasks->contains($task));
	}
	
	/** @test */
	public function it_can_invite_a_user() 
	{
		$project = ProjectTestFactory::create();	// Given I have a project
		$project->invite($newUser = factory(User::class)->create());

		
		// Assert new user is one of the "members" that can work on projects
		$this->assertTrue($project->members->contains($newUser));
	}
	
}
