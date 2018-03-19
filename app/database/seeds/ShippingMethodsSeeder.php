<?php

class ShippingMethodsSeeder extends DatabaseSeeder
{
	protected $methods = array(
		array(
			'name' => 'UPS',
			'description' => 'United Parcel Service',
		),
		array(
			'name' => 'FedEx',
			'description' => "Federal Express"
		),
		array(
			'name' => 'Delivery',
			'description' => "Local Delivery"
		),
		array(
			'name' => 'Pickup',
			'description' => "Local Pickup"
		)
	);
	
	public function run()
	{
	    Eloquent::unguard();
		DB::unprepared("SET foreign_key_checks=0");
		DB::table('shipping_types')->truncate();
		DB::unprepared("SET foreign_key_checks=1");
	    
		foreach($this->methods as $m) {
			$shippingMethod = ShippingMethod::create($m);
			$shippingMethod->save();
		}
	}
}
