<?php

namespace LookupSeeder;

use \Eloquent;
use \DB;
use \Seeder;

class MountsSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
	    Eloquent::unguard();
		
		DB::unprepared("SET foreign_key_checks=0");	    
		DB::table('mounts')->truncate();
        DB::unprepared("SET foreign_key_checks=1");

		DB::table('mounts')->insert(array (
			0 => 
			array (
				'id' => 1,
				'code' => 'IB',
				'description' => 'IB',
			),
			1 => 
			array (
				'id' => 2,
				'code' => 'OB',
				'description' => 'OB',
			),
		));
	}

}
