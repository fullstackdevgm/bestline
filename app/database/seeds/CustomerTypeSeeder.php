<?php

class CustomerTypeSeeder extends DatabaseSeeder
{
	protected $_customerTypes = array(
		array(
			'name' => "Normal Customer",
			'description' => "Average Customer",
			'order_pricemod_equation' => null
		),
		array(
			'name' => "Good Customer",
			'description' => "Good Customer",
			'order_pricemod_equation' => "total * .9"
		),
		array(
			'name' => 'Awesome Customer',
			'description' => 'Awesome Customer',
			'order_pricemod_equation' => 'total * .8'
		)
	);
	
	public function run()
	{
	    Eloquent::unguard();
	    
		DB::unprepared("SET foreign_key_checks=0");
		DB::table('customer_types')->truncate();
		DB::unprepared("SET foreign_key_checks=1");
	    
		foreach($this->_customerTypes as $type) {
			$typeObj = CustomerType::create($type);
			$typeObj->save();
		}
	}
}
