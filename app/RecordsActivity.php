<?php 

namespace App;

trait RecordsActivity 
{
	public $oldAttributes = [];
	
	public static function bootRecordsActivity() 
	{		
	
		foreach (self::recordableEvents() as $event) {
			static::$event(function ($model) use ($event) {                
		 		$model->recordActivity($model->activityDescription($event));
			 });
			 
			if ($event === 'updated') {
				static::updating(function ($model) {
					$model->oldAttributes = $model->getOriginal();			
				});
			}
		}
	}   
	
	protected function activityDescription($description)
	{
		return "{$description}_" . strtolower(class_basename($this));
	}
	
	protected static function recordableEvents()
	{
		if (isset(static::$recordableEvents)) {
        	return static::$recordableEvents;
        } 
        return ['created', 'updated'];				
	}

	/** 
	 * Record activity for a project
	 * 
	 * @param string $description
	 */
	public function recordActivity($description)
    {
        $this->activity()->create([
			'user_id' => $this->activityOwner()->id,
            'description' => $description,
			'changes' => $this->activityChanges(),
			'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project_id
        ]);
	}
	
	protected function activityOwner()
	{
		// For now, assume the project owner
		// $project = $this->project ?? $this;
		// return $project->owner;
		
		// Refactors [inline] to: 
		return ($this->project ?? $this)->owner;
			
	}
	
	
	
	public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
	}	
	
	protected function activityChanges()
	{
		if ($this->wasChanged()) {
			return [
				'before' => array_except(array_diff($this->oldAttributes, $this->getAttributes()), 'updated_at'),
				'after' => array_except($this->getChanges(), 'updated_at')
			];
		}
	}
	
}