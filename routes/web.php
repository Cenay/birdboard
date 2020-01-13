<?php
/*
Laravel has a series of "events" that fire during various operations,
like when something is created (see below)
*/
// \App\Project::created(function ($project) {
//     \App\Activity::create([
//         'project_id' => $project->id,
//         'description' => 'created'
//     ]);
// });
// \App\Project::updated(function ($project) {
//     \App\Activity::create([
//         'project_id' => $project->id,
//         'description' => 'updated'
//     ]);
// });

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('projects', 'ProjectsController');
	
    Route::post('/projects/{project}/tasks', 'ProjectTasksController@store');
	Route::patch('/projects/{project}/tasks/{task}', 'ProjectTasksController@update');
	
    Route::get('/home', 'HomeController@index')->name('home');
});

Auth::routes();
