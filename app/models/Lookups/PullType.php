<?php

namespace Lookups;

class PullType extends \Eloquent
{
    protected $table = "pull_types";

	public function hardware(){

        return $this->belongsToMany('Lookups\Hardware');
    }
}