<?php

namespace OrderSeeder\FabricSeeder\OptionSeeder;

use \Order;
use \Option;
use \Option\Data as OptionData;
use \Eloquent;
use \DB;
use \DatabaseSeeder;

class DataSeeder extends DatabaseSeeder
{
	public function run()
	{
		$fabricOptionData = $this->getFabricOptionData();

		foreach($fabricOptionData as $fabricOption){

			$fabricOptionObj = OptionData::create($fabricOption);
			$fabricOptionObj->save();
		}
	}

	protected function getFabricOptionData(){

		$fabricOptionData = [];

		//setup Corcoran/Cotter/Mom\'s Bath fabric options
		$order = Order::where('sidemark', '=', 'Corcoran/Cotter/Mom\'s Bath')->firstOrFail();
		$fabricOptionData[] = array(
			'size_bottom' => 0.375,
			'size_sides' => 0.375,
			'parent_option_id' => Option::where('name', '=', 'Banding - Sides, Bottom')->firstOrFail()->id,
			'order_fabric_option_id' => $order->fabricByType('embellishment')->options()->first()->id,
		);

		//setup JRD/Presidio/DR fabric options
		$order = Order::where('sidemark', '=', 'JRD/Presidio/DR')->firstOrFail();
		$fabricOptionData[] = array(
			'size_bottom' => 2,
			'inset_size_bottom' => 1,
			'parent_option_id' => Option::where('name', '=', 'Banding - Inset Bottom')->firstOrFail()->id,
			'order_fabric_option_id' => $order->fabricByType('embellishment')->options()->first()->id,
		);

		return $fabricOptionData;
	}
}