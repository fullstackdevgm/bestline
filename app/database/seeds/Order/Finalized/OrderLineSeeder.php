<?php

namespace OrderSeeder\FinalizedOrderSeeder;

use \Eloquent;
use \DB;
use \DatabaseSeeder;
use \Log;
use \Order\Finalized\LineItem as FinalizedOrderLine;

class OrderLineSeeder extends DatabaseSeeder
{
	public function run()
	{
		Eloquent::unguard();

		DB::unprepared("SET foreign_key_checks=0");
		FinalizedOrderLine::truncate();
		DB::unprepared("SET foreign_key_checks=1");
	}
}