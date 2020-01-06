<?php
namespace Tests\Setup;

use App\User;
use App\Task;
use App\Project;

class ProjectTestFactory
{
    protected $tasksCount = 0;      // Default to zero and "call" it if the method used includes the withTasks(*)
    protected $user = '';

    // To make it fluent, helper function to determine if we should create tasks.
    public function withTasks($count)
    {
        $this->tasksCount = $count;
        return $this;
    }

    public function ownedBy($user)
    {
        $this->user = $user;
        return $this;
    }

    public function create()
    {
        $project = factory(Project::class)->create([
            'owner_id' => $this->user ?: factory(User::class)
        ]);

        // If tasksCount = 0, this won't persist (as expected)
        factory(Task::class, $this->tasksCount)->create([
            'project_id' => $project->id
        ]);
        return $project;
    }
}
