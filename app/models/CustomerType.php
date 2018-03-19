<?php

class CustomerType extends Eloquent
{
	protected $table = "customer_types";
	
	public function company()
	{
	    return $this->hasOne('Company', 'customer_type_id');
	}
}