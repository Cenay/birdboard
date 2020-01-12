<?php

namespace Tests\Unit;

use App\Task;
use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class TaskTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function it_belongs_to_a_project()
    {
        $task = factory(Task::class)->create();
        $this->assertInstanceOf(Project::class, $task->project);
		
    }
	
    /** @test */
    public function it_has_a_path()
    {
		$task = factory(Task::class)->create();
        $this->assertEquals('/projects/' . $task->project->id . '/tasks/' . $task->id, $task->path());
	}
	
	/** @test */
	public function it_can_be_completed() 
	{
		$task = factory(Task::class)->create();
		$this->assertFalse($task->completed);
		
		$task->complete();
		
		$this->assertTrue($task->fresh()->completed);		
	}   
	
	/** @test */
	public function it_can_be_marked_as_incomplete() 
	{
		// Arrange
		$task = factory(Task::class)->create();
		$task->complete();
		
		// Act
		$task->incomplete();
		
		// Assert
		$this->assertFalse($task->fresh()->completed);		
	}   
}
