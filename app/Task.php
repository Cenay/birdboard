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

    // protected static function boot()
    // {
    // 	parent::boot();
    // 	static::create(function($task) {
    // 		Activity::create([
    // 			'project_id' => $task->project->id,
    // 			'description' => 'created_task'
    // 		]);
    // 	});
    // }

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
        $this->project->recordActivity('completed_task');
    }

    public function incomplete()
    {
        $this->update(['completed' => false]);
        $this->project->recordActivity('marked_task_incomplete');
	}

	public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
	}
	
    public function recordActivity($description)
    {
		// Refactor
		// Because we have an activity function (above), and project is created automatically
		// We can just create on that "relationship"
        // Activity::create([
        //     'project_id' => $this->id,
        //     'description' => $type
		// ]);
		
		// Next refactor, make the variables line up
		// $this->activity()->create(['description' => $type]); // Rename is function definition as well
		
		// Next refactory, compact the call out
		// $this->activity()->create(['description' => $description]);
		
		// $this->activity()->create(compact('description'));	
		// Refactor to deal with the new polymorphic relationship
		$this->activity()->create([
			'project_id' => $this->project_id,
			'description' => $description
		]);	
	}
	
}
