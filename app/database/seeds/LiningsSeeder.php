<?php

class LiningsSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
	    Eloquent::unguard();
		
		DB::unprepared("SET foreign_key_checks=0");	    
		\DB::table('linings')->truncate();
        	DB::unprepared("SET foreign_key_checks=1");
		
		\DB::table('linings')->insert(array (
			0 => 
			array (
				'id' => 1,
				'code' => 'white',
				'description' => 'White',
			    'pricing_type' => 'sqft'
			),
			1 => 
			array (
				'id' => 2,
				'code' => '116_54',
				'description' => '116" 54',
			    'pricing_type' => 'sqft'
			),
			2 => 
			array (
				'id' => 3,
				'code' => 'blackout_54',
				'description' => 'Blackout 54',
			    'pricing_type' => 'sqft'
			),
			3 => 
			array (
				'id' => 4,
				'code' => 'com_blackout',
				'description' => 'COM Blackout',
			    'pricing_type' => 'sqft'
			),
			4 => 
			array (
				'id' => 5,
				'code' => 'stock_54',
				'description' => 'Stock 54',
			    'pricing_type' => 'sqft'
			),
			5 => 
			array (
				'id' => 6,
				'code' => 'theirs',
				'description' => 'Theirs',
			    'pricing_type' => 'sqft'
			),
			6 => 
			array (
				'id' => 7,
				'code' => 'theirs_thermal',
				'description' => 'Theirs Thermal',
			    'pricing_type' => 'sqft'
			),
			7 => 
			array (
				'id' => 8,
				'code' => 'thermal_suede_54',
				'description' => 'Thermal Suede 54',
			    'pricing_type' => 'sqft'
			),
			8 => 
			array (
				'id' => 9,
				'code' => 'unlined',
				'description' => 'Unlined',
			    'pricing_type' => 'sqft'
			),
		));
	}

}
