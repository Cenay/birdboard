<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectTestFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvitationsTest extends TestCase
{
	use RefreshDatabase;
	
	/** @test */
	public function a_project_can_invite_a_user() {
		
		
		$project = ProjectTestFactory::create();	// Given I have a project
				
		$project->invite($newUser = factory(User::class)->create());	// Owner of project invite another user
		
		
		// Then, that new user will have permission to add tasks
		$this->signIn($newUser);	// Sign in new user
		
		// Can the new user post a new task?
		$this->post(action('ProjectTasksController@store', $project), $task = ['body' => 'Foo task']);
		
		$this->assertDatabaseHas('tasks', $task);
		
	}
	
}
