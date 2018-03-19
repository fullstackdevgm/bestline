<?php

namespace LookupSeeder;

use \Eloquent;
use \DB;
use \Seeder;
use \Lookups\ValanceType;
use \Option;

class ValanceTypeSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run(){

	    Eloquent::unguard();
		DB::unprepared("SET foreign_key_checks=0");
		DB::table('valance_types')->truncate();
        DB::unprepared("SET foreign_key_checks=1");

		$valanceType = $this->getValanceTypes();
		foreach($valanceType as $item){

			$valanceTypeObj = ValanceType::create($item);
			$valanceTypeObj->save();
		}
	}

	protected function getValanceTypes(){

		$valanceTypes = [];

		$valanceTypes[] = array (
			'name' => 'Box Pleated',
			'type' => 'slug_box_pleated',
		);
		$valanceTypes[] = array (
			'name' => 'Flat',
			'type' => 'slug_flat',
		);
		$valanceTypes[] = array (
			'name' => 'Scalloped',
			'type' => 'slug_scalloped',
		);

		return $valanceTypes;
	}
}
