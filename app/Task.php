<?php

namespace App;

use App\Activity;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * Attributes to guard against mass assignment.
     *
     * @var array
     */
    protected $guarded = [];
    protected $touches = ['project'];
    protected $casts = [
        'completed' => 'boolean'
    ];

    public function path()
    {
        return "/projects/{$this->project->id}/tasks/{$this->id}";
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Mark a Task Complete - records the activity
     * @return void
     */
    public function complete()
    {
        $this->update(['completed' => true]);
        $this->recordActivity('completed_task');
    }

    public function incomplete()
    {
        $this->update(['completed' => false]);
        $this->recordActivity('marked_task_incomplete');
	}	
	
    public function recordActivity($description)
    {
		// Copied from Project
		// Refactor to deal with the new polymorphic relationship
		$this->activity()->create([
			'project_id' => $this->project_id,
			'description' => $description
		]);	
	}
	
	public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
	}
	
}
