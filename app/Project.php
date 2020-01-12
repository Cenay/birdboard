<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [];

    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function addTask($body)
    {
        return $this->tasks()->create(compact('body'));
    }

    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
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
		
		$this->activity()->create(compact('description'));
		

    }
}
