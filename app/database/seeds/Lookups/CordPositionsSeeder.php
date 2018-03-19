<?php

namespace LookupSeeder;

use \Eloquent;
use \DB;
use \Seeder;

class CordPositionsSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
	    Eloquent::unguard();

		DB::unprepared("SET foreign_key_checks=0");
		DB::table('cord_positions')->truncate();
		DB::unprepared("SET foreign_key_checks=1");
	    
		DB::table('cord_positions')->insert(array (
			0 => 
			array (
				'id' => 1,
				'code' => 'L',
				'description' => 'Left',
			),
			1 => 
			array (
				'id' => 2,
				'code' => 'R',
				'description' => 'Right',
			),
			2 => 
			array (
				'id' => 3,
				'code' => 'L/R',
				'description' => 'Left/Right',
			),
			3 => 
			array (
				'id' => 4,
				'code' => 'R/L',
				'description' => 'Right/Left',
			),
			4 => 
			array (
				'id' => 5,
				'code' => 'L/L',
				'description' => 'Left/Left',
			),
			5 => 
			array (
				'id' => 6,
				'code' => 'R/R',
				'description' => 'Right/Right',
			),
		));
	}

}
