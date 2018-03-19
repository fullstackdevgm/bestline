<?php

namespace OrderSeeder;

use \Order\FinalizedOrder;
use \Eloquent;
use \DB;
use \DatabaseSeeder;

class FinalizedOrderSeeder extends DatabaseSeeder
{
	public function run()
	{
		Eloquent::unguard();

		DB::unprepared("SET foreign_key_checks=0");
		FinalizedOrder::truncate();
		DB::unprepared("SET foreign_key_checks=1");
	}
}