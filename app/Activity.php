<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
	protected $guarded = [];
	protected $casts = [
		'changes' => 'array'
	];
	public $user_name = '';
	
	public function subject() 
	{
		return $this->morphTo();
	}   
	
	public function user() 
	{
		return $this->belongsTo(User::class);
	}   
	
	public function user_name() 
	{
	
		//dd(Auth::user()->name);
		
		// $this->user->name = "Cenay"
		// Auth::user()->name = "Cenay" 
		
		// is auth user the person that created this activity = yes -> you
		if ($this->user->name === Auth::user()->name) {
			return "You";			
		} else {
			return $this->user->name;
		}
	}   
}
