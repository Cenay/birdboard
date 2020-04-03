<?php
/*
$table->bigIncrements('id');
$table->unsignedBigInteger('owner_id');
$table->string('title');
$table->text('description');
$table->string('notes')->nullable();
$table->timestamps();

$table->foreign('owner_id')
    ->references('id')
    ->on('users')
    ->onDelete('cascade');
*/

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use RecordsActivity;	// Bring in our new trait

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

    public function invite(User $user)
    {
        $this->members()->attach($user);
    }

    public function members()
    {
        // Is it true that a project can have many members
        // and also a member can have many projects?
        // Pivot table required
        // Convention says it's project_user (the two tables involved, sorted alpha, in singular form)
        // We can override it by passing the new table name to the belongsToMany
        return $this->belongsToMany(User::class, 'project_members')->withTimestamps();	// Override the pivot table name
    }
}
