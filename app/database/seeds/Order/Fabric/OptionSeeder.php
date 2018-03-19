<?php

namespace OrderSeeder\FabricSeeder;

use \Order;
use \Order\Fabric\Option as FabricOption;
use \Option;
use \Eloquent;
use \DB;
use \DatabaseSeeder;

class OptionSeeder extends DatabaseSeeder
{
	public function run()
	{
		Eloquent::unguard();

		DB::unprepared("SET foreign_key_checks=0");
		FabricOption::truncate();
		DB::unprepared("SET foreign_key_checks=1");

		$fabricOptions = $this->getFabricOptions();

		foreach($fabricOptions as $fabricOption){

			$data = (isset($fabricOption['data']))? $fabricOption['data'] : null;
			unset($fabricOption['data']);

			$fabricOptionObj = FabricOption::create($fabricOption);
			$fabricOptionObj->save();

			if($data){
			    $data['order_fabric_option_id'] = $fabricOptionObj->id;
			    $fabricOptionObj->data()->create($data);
			    $fabricOptionObj->data->save();
			}
		}
	}

	protected function getFabricOptions(){

		$fabricOptions = [];

		//setup Corcoran/Cotter/Nook fabric options
		$order = Order::where('sidemark', '=', 'Corcoran/Cotter/Nook')->firstOrFail();
		$fabricOptions[] = array(
			'order_id' => $order->id,
			'order_fabric_id' => $order->fabricByType('embellishment')->id,
			'option_id' => Option::where('name', '=', 'Trim')->firstOrFail()->id,
			'sub_option_id' => Option::where('name', '=', 'Trim - Bottom')->firstOrFail()->id,
		);

		//setup Corcoran/Cotter/Mom\'s Bath fabric options
		$order = Order::where('sidemark', '=', 'Corcoran/Cotter/Mom\'s Bath')->firstOrFail();
		$fabricOptions[] = array(
			'order_id' => $order->id,
			'order_fabric_id' => $order->fabricByType('embellishment')->id,
			'option_id' => Option::where('name', '=', 'Banding')->firstOrFail()->id,
			'sub_option_id' => Option::where('name', '=', 'Banding - Sides, Bottom')->firstOrFail()->id,
		);

		//setup JRD/Presidio/DR fabric options
		$order = Order::where('sidemark', '=', 'JRD/Presidio/DR')->firstOrFail();
		$fabricOptions[] = array(
			'order_id' => $order->id,
			'order_fabric_id' => $order->fabricByType('embellishment')->id,
			'option_id' => Option::where('name', '=', 'Banding')->firstOrFail()->id,
			'sub_option_id' => Option::where('name', '=', 'Banding - Inset Bottom')->firstOrFail()->id,
		);

		//setup Ratna/Girls order options
		$order = Order::where('sidemark', '=', 'Ratna/Girls')->firstOrFail();
		$fabricOptions[] = array(
            'order_id' => $order->id,
            'order_fabric_id' => $order->fabricByType('embellishment')->id,
            'option_id' => Option::where('name', '=', 'Banding')->firstOrFail()->id,
            'sub_option_id' => Option::where('name', '=', 'Banding - Inset Sides, Bottom')->firstOrFail()->id,
            'data' => [
                'parent_option_id' => Option::where('name', '=', 'Banding - Inset Sides, Bottom')->firstOrFail()->id,
                'size_bottom' => 1.5,
                'size_sides' => 1.5,
                'inset_size_bottom' => 2,
                'inset_size_sides' => 2,
            ]
        );

		//setup Dreiling/MBR order options
		$order = Order::where('sidemark', '=', 'Dreiling/MBR')->firstOrFail();
		$fabricOptions[] = array(
            'order_id' => $order->id,
            'order_fabric_id' => $order->fabricByType('embellishment')->id,
            'option_id' => Option::where('name', '=', 'Tassel Trim')->firstOrFail()->id,
            'sub_option_id' => Option::where('name', '=', 'Tassel Trim - Bottom')->firstOrFail()->id,
            'data' => [
                'parent_option_id' => Option::where('name', '=', 'Banding - Inset Sides, Bottom')->firstOrFail()->id,
                'size_bottom' => 1,
            ]
        );

		//setup Punzi/Klausner/Olivia order options
		$order = Order::where('sidemark', '=', 'Punzi/Klausner/Olivia')->firstOrFail();
		$fabricOptions[] = array(
            'order_id' => $order->id,
            'order_fabric_id' => $order->fabricByType('lining')->id,
            'option_id' => Option::where('name', '=', 'Blackout')->firstOrFail()->id,
            'sub_option_id' => Option::where('name', '=', 'Blackout Fabric and Application')->firstOrFail()->id,
            'data' => [
                'parent_option_id' => Option::where('name', '=', 'Blackout Fabric and Application')->firstOrFail()->id
            ]
        );

		return $fabricOptions;
	}
}