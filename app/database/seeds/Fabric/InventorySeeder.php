<?php

namespace FabricSeeder;

use \Inventory\Fabric as InventoryFabric;
use \Eloquent;
use \DB;
use \DatabaseSeeder;

class InventorySeeder extends DatabaseSeeder
{
	public function run()
	{
		Eloquent::unguard();

		DB::unprepared("SET foreign_key_checks=0");
		InventoryFabric::truncate();
		DB::unprepared("SET foreign_key_checks=1");

		$this->addInventory();
	}

	protected function addInventory(){
	    $inventoryLine = array(
	        array(
	            'fabric_id' => 32,
	            'quantity' => 360,
	            'adjustment' => 360,
	            'reason' => 'Initial Addition',
	            'by_user_id' => 1,
	        ),
	        array(
	            'fabric_id' => 33,
	            'quantity' => 78,
	            'adjustment' => 78,
	            'reason' => 'Initial Addition',
	            'by_user_id' => 1,
	        ),
	    );

	    foreach($inventoryLine as $inventory){

	    	$inventoryObj = InventoryFabric::create($inventory);
	    	$inventoryObj->save();
	    }
	}
}