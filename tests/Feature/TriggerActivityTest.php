<?php

namespace Tests\Feature;

use App\Task;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectTestFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_a_project()
    {
        $project = ProjectTestFactory::create();

        $this->assertCount(1, $project->activity);
        $this->assertEquals('created_project', $project->activity[0]->description);
    }

    /** @test */
    public function updating_a_project()
    {
        $project = ProjectTestFactory::create();
        $project->update(['title' => 'Changed']);

        $this->assertCount(2, $project->activity);
        $this->assertEquals('updated_project', $project->activity->last()->description);
    }

    /** @test */
    public function creating_a_new_task()
    {
        $project = ProjectTestFactory::create();
        $project->addTask('Some task');

        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('created_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
        });
    }

    /** @test */
    public function completing_a_task()
    {
        $project = ProjectTestFactory::withTasks(1)->create();
        // $project = ProjectTestFactory::create();
        // $project->addTask('Some task');

        $this->actingAs($project->owner)
             ->patch($project->tasks[0]->path(), [
                 'body' => 'foobar',
                 'completed' => true
             ]);

        $this->assertCount(3, $project->activity);

        // $project->completeTask('Some task');
        $this->assertEquals('completed_task', $project->activity->last()->description);
    }

    /** @test */
    public function incompleting_a_task()
    {
        $project = ProjectTestFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
             ->patch($project->tasks[0]->path(), [
                 'body' => 'foobar',
                 'completed' => true
             ]);

        $this->assertCount(3, $project->activity);

        $this->patch($project->tasks[0]->path(), [
            'body' => 'foobar',
            'completed' => false
        ]);
        $this->assertCount(4, $project->fresh()->activity);
        $this->assertEquals('marked_task_incomplete', $project->fresh()->activity->last()->description);
    }

    /** @test */
    public function deleting_a_task()
    {
        $project = ProjectTestFactory::withTasks(1)->create();
        $project->tasks[0]->delete();

        $project->refresh();

        $this->assertCount(3, $project->activity);

        $this->assertEquals('deleted_task', $project->activity->last()->description);
    }
}
