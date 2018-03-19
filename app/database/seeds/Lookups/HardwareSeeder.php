<?php

namespace LookupSeeder;

use \Eloquent;
use \DB;
use \Seeder;
use \Lookups\Hardware;
use \Option;

class HardwareSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run(){

	    Eloquent::unguard();
		DB::unprepared("SET foreign_key_checks=0");	    
		DB::table('hardware')->truncate();
        DB::unprepared("SET foreign_key_checks=1");

		$hardware = $this->getHardware();
		foreach($hardware as $item){

			$hardwareObj = Hardware::create($item);
			$hardwareObj->save();
		}
	}

	protected function getHardware(){

		$hardware = [];

		$hardware[] = array (
			'code' => 'standard',
			'description' => 'Standard',
			'related_option_id' => NULL,
		);
		$hardware[] = array (
			'code' => 'cord_lock',
			'description' => 'Cord Lock',
			'related_option_id' => NULL,
		);
		$hardware[] = array (
			'code' => 'motorization',
			'description' => 'Motorization',
			'related_option_id' => Option::where('name', '=', 'Motorization')->firstOrFail()->id,
		);
		$hardware[] = array (
			'code' => 'continuous_cord',
			'description' => 'Continuous Cord',
			'related_option_id' => Option::where('name', '=', 'Continuous Cord')->firstOrFail()->id
		);

		return $hardware;
	}
}
