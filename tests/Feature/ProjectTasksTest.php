<?php

namespace Tests\Feature;

use App\Project;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectTestFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_add_tasks_to_projects()
    {
        $project = factory('App\Project')->create();
        $this->post($project->path() . '/tasks')->assertRedirect('login');
    }

    /** @test */
    public function only_the_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();
        $project = factory('App\Project')->create();    // Project by ANOTHER user

        // Should return 403 since adding tasks to project by another user isn't allowed
        $this->post($project->path() . '/tasks', ['body' => 'Test task'])
            ->assertStatus(403);

        // Should be missing since it should NOT allow the post
        $this->assertDatabaseMissing('tasks', ['body' => 'Test task']);
    }

    /** @test */
    public function only_the_owner_of_a_project_may_update_a_task()
    {
        // Arrange
        $this->signIn();
        $project = ProjectTestFactory::withTasks(1)->create();

        // Act (and Assert)
        $this->patch($project->tasks[0]->path(), ['body' => 'changed'])
            ->assertStatus(403);

        // Assert
        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }

    /** @test */
    public function a_project_can_have_tasks()
    {
        // Arrange
        $project = ProjectTestFactory::create();

        // Act
        $this->actingAs($project->owner)
            ->post($project->path() . '/tasks', ['body' => 'Test task']);

        // Assert
        $this->get($project->path())
            ->assertSee('Test task');
    }

    /** @test */
    public function a_task_can_be_updated()
    {

        // Arrange
        $project = ProjectTestFactory::withTasks(1)->create();

        // Act
        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'changed',
                'completed' => true
        ]);

        // Assert
        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => true
        ]);
    }

    public function a_task_requires_a_body()
    {
        // Arrange
        $project = ProjectTestFactory::create();
        $attributes = factory('App\Task')->raw(['body' => '']);

        // Act and Assert
        $this->actingAs($project->owner)
            ->post($project->path() . '/tasks', $attributes)
            ->assertSessionHasErrors('body'); // ASSERTION
    }
}
