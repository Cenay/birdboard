<?php

namespace App\Observers;

use App\Task;
use App\Project;
use App\Activity;

class TaskObserver
{
    public function created(Task $task) 
	{
		// $task->project->recordActivity('created_task');
		$task->recordActivity('created_task');
	}  
	
	public function deleted(Task $task) 
	{
		// $task->project->recordActivity('deleted_task');
		$task->recordActivity('deleted_task');
	}  
	
}
