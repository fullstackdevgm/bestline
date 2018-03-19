<?php

class State extends Eloquent
{
	protected $table = 'states';
	public $timestamps = false;
	
	static public function getStatesList()
	{
		$states = static::orderBy('abbreviation')->lists('state', 'abbreviation');
		
		return $states;
	}
	
	static public function getStateAbbreviations()
	{
	    return static::orderBy('abbreviation')->lists('abbreviation');
	}
}