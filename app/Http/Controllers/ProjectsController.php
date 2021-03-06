<?php

namespace App\Http\Controllers;

use App\Project;

class ProjectsController extends Controller
{
    /**
     * View all projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $projects = auth()->user()->projects;
        $projects = auth()->user()->accessibleProjects();
        return view('projects.index', compact('projects'));
    }

    /**
     * Create a new project.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Show a single project.
     *
     * @param Project $project
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Project $project)
    {
        $this->authorize('update', $project);
        return view('projects.show', compact('project'));
    }

    /**
     * Persist a new project.
     *
     * @return mixed
     */
    public function store()
    {
        $project = auth()->user()->projects()->create($this->validateRequest());

        return redirect($project->path());
    }

    /**
     * Update the project.
     *
     * @param  Project $project
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Project $project)
    {
        $this->authorize('update', $project);
        $project->update($this->validateRequest());

        return redirect($project->path());
    }

    /**
     * Edit the project.
     *
     * @param  Project $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        return view('projects.edit', compact('project'));
	}
	
	public function destroy(Project $project) 
	{
        $this->authorize('update', $project);
		
		$project->delete();
		return redirect("/projects");
	}   
		
    /**
     * Validate the request attributes
     *
     * @return array
     */
    protected function validateRequest()
    {
        return request()->validate([
            'title'       => 'sometimes|required',
            'description' => 'sometimes|required|max:100',
            'notes'       => 'nullable'
            ]);
    }

    /* Jeffrey refactored to bring in a form request module so the update looked like this:
    public function update(UpdatedProjectRequest $request, Project $project)
    {
        $request->persist();
        return redirect($project->path());
    }
    Where the persist() method is inside the UpdatedProjectRequest file
    It also calls the validation, and as long as it validates, will persist the changes
    See this video for more on
    Form Objects
    Tap
    HigherOrderTap
    https://laracasts.com/series/build-a-laravel-app-with-tdd/episodes/19


    */
}
