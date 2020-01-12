<?php

namespace App\Http\Controllers;

use App\Task;
use App\Project;

class ProjectTasksController extends Controller
{
    /**
     * Add a task to the given project.
     *
     * @param \App\Project $project
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Project $project)
    {
        $this->authorize('update', $project);

        request()->validate(['body' => 'required']);
        $project->addTask(request('body'));
        return redirect($project->path());
    }

    /**
     * Update a task on the given project.
     *
     * @param \App\Project $project
     * @param \App\Task $task
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Project $project, Task $task)
    {
        $this->authorize('update', $task->project);

        // $attributes = request()->validate(['body' => 'required']);
        // $task->update($attributes);

        // Refactor (to inline it)
        $task->update(request()->validate(['body' => 'required']));

        // Refactor
        // if (request('completed')) {
        // 	$task->complete();
        // } else {
        // 	$task->incomplete();
        // }
        // $method = request('completed') ? 'complete' : 'incomplete';
        // $task->$method();
        // Refactor again
        request('completed') ? $task->complete() : $task->incomplete();

        // $task->update([
        //     'body' => request('body'),
        //     'completed' => request()->has('completed')
        // ]);
        return redirect($project->path());
    }
}
