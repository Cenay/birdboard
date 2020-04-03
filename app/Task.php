<?php

namespace App;

use App\Activity;
use Illuminate\Database\Eloquent\Model;


class Task extends Model
{

	use RecordsActivity; 	// Bring in our new trait

	protected $guarded = [];
    protected $touches = ['project'];
	protected $casts   = ['completed' => 'boolean'];

	protected static $recordableEvents = ['created', 'deleted'];

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
        $this->recordActivity('incompleted_task');
	}

}
