<?php

namespace Tests\Unit;

use App\User;
use App\Project;
use App\Activity;
use Facades\Tests\Setup\ProjectTestFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
	use RefreshDatabase;
	
	/** @test */
	public function it_has_a_user() 
	{
		$user = $this->signIn();
		// $project = factory(Project::class)->create();
		
		// $this->assertInstanceOf(User::class, $project->activity->first()->user);
		$project = ProjectTestFactory::ownedBy($user)->create();
		$this->assertEquals($user->id, $project->activity->first()->user->id);
		
	}   
	
}
