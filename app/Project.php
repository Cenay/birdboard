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
		// return $this->hasMany(Activity::class)->latest();
		return $this->morphMany(Activity::class, 'subject')->latest();
	}
	
    public function recordActivity($description)
    {
		$this->activity()->create([
			'project_id' => $this->id,
			'description' => $description	
		]);
		

    }
}
