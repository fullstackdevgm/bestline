<?php

namespace Lookups;

use FormulaInterpreter\Compiler;

class Hardware extends \Eloquent
{
    protected $table = "hardware";
    
    public function relatedOption(){
		return $this->belongsTo('Option', 'related_option_id');
	}

	public function pullTypes(){

        return $this->belongsToMany('Lookups\PullType');
    }
}