<?php

class StationSeeder extends DatabaseSeeder
{	
	public function run()
	{
	    Eloquent::unguard();
		DB::unprepared("SET foreign_key_checks=0");	    
	    DB::table('stations')->truncate();
		DB::unprepared("SET foreign_key_checks=1");

		$stations = $this->getStations();

		foreach($stations as $station) {
			$state = Station::create($station);
			$state->save();
		}
	}

	private function getStations(){

		$stations = [];

		$stations[] = [
			'name' => 'Cutter',
			'code' => 'cutter',
			'sort_order' => 100,
		];
		$stations[] = [
			'name' => 'Sidehemist',
			'code' => 'sidehemist',
			'sort_order' => 200,
		];
		$stations[] = [
			'name' => 'Seamstress',
			'code' => 'seamstress',
			'sort_order' => 300,
		];
		$stations[] = [
			'name' => 'Cleaner',
			'code' => 'cleaner',
			'sort_order' => 400,
		];
		$stations[] = [
			'name' => 'Assembler',
			'code' => 'assembler',
			'sort_order' => 500,
		];
		$stations[] = [
			'name' => 'Boxer',
			'code' => 'boxer',
			'sort_order' => 600,
		];

		return $stations;
	}
}
