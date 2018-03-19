<?php

namespace LookupSeeder;

use \Eloquent;
use \DB;
use \Seeder;
use \Lookups\PullType;

class PullTypesSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
	    Eloquent::unguard();
		DB::unprepared("SET foreign_key_checks=0");	    
		DB::table('pull_types')->truncate();
		DB::unprepared("SET foreign_key_checks=1");

		$pullTypes = $this->getPullTypes();

		foreach($pullTypes as $pullType){

			$hasHardware = (isset($pullType['hardware']) && count($pullType['hardware']) > 0);
            $hardwareSlugs = ($hasHardware)? $pullType['hardware'] : [];
            if($hasHardware){
                unset($pullType['hardware']);
            }

			$pullTypeObj = PullType::create($pullType);
			$pullTypeObj->save();

            if(count($hardwareSlugs) > 0){
                $hardware = DB::table('hardware')->whereIn('code', $hardwareSlugs)->lists('id');
                $pullTypeObj->hardware()->sync($hardware);
            }
		}

	}
	protected function getPullTypes(){

		$pullTypes = [];

		$pullTypes[] = array (
			'description' => 'White Tassel',
			'hardware' => array('standard', 'cord_lock'),
			//'related_option_id' => 73,
		);
		$pullTypes[] = array (
			'description' => 'Bone Tassel',
			'hardware' => array('standard', 'cord_lock'),
		);
		$pullTypes[] = array (
			'description' => 'Pecan Tassel',
			'hardware' => array('standard', 'cord_lock'),
		);
		$pullTypes[] = array (
			'description' => 'Walnut Tassel',
			'hardware' => array('standard', 'cord_lock'),
		);
		$pullTypes[] = array (
			'description' => 'White Cloth',
			'hardware' => array('continuous_cord'),
		);
		$pullTypes[] = array (
			'description' => 'Ivory Cloth',
			'hardware' => array('continuous_cord'),
		);
		$pullTypes[] = array (
			'description' => 'White Plastic',
			'hardware' => array('continuous_cord'),
		);
		$pullTypes[] = array (
			'description' => 'Ivory Plastic',
			'hardware' => array('continuous_cord'),
		);
		$pullTypes[] = array (
			'description' => 'Antique Brass Metal',
			'hardware' => array('continuous_cord'),
		);
		$pullTypes[] = array (
			'description' => 'Bronze Metal',			
			'hardware' => array('continuous_cord'),
		);
		$pullTypes[] = array (
			'description' => 'Brass Metal',
			'hardware' => array('continuous_cord'),
		);
		$pullTypes[] = array (
			'description' => 'Gold Metal',
			'hardware' => array('continuous_cord'),
		);
		$pullTypes[] = array (
			'description' => 'Silver Metal',
			'hardware' => array('continuous_cord'),
		);
		$pullTypes[] = array (
			'description' => 'Telis 1 Channel Remote',
			'hardware' => array('motorization'),
		);
		$pullTypes[] = array (
			'description' => 'Telis 4 Channel Remote',
			'hardware' => array('motorization'),
		);
		$pullTypes[] = array (
			'description' => 'Deco Flex 1 Channel Wall Switch',
			'hardware' => array('motorization'),
		);
		$pullTypes[] = array (
			'description' => 'Deco Flex 2 Channel Wall Switch',
			'hardware' => array('motorization'),
		);
		$pullTypes[] = array (
			'description' => 'Deco Flex 3 Channel Wall Switch',
			'hardware' => array('motorization'),
		);
		$pullTypes[] = array (
			'description' => 'Deco Flex 4 Channel Wall Switch',
			'hardware' => array('motorization'),
		);
		$pullTypes[] = array (
			'description' => 'Deco Flex 5 Channel Wall Switch',
			'hardware' => array('motorization'),
		);
		$pullTypes[] = array (
			'description' => 'Solar Recharge Kit',
			'hardware' => array('motorization'),
		);

		return $pullTypes;
	}
}
