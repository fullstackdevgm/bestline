<?php

namespace OrderSeeder\OrderLineSeeder;

use \Eloquent;
use \DB;
use \DatabaseSeeder;
use \Order;
use \Log;
use \Order\LineItem\Option as OrderLineOption;

class OptionSeeder extends DatabaseSeeder
{
	public function run()
	{
		Eloquent::unguard();

		DB::unprepared("SET foreign_key_checks=0");
		OrderLineOption::truncate();
		DB::unprepared("SET foreign_key_checks=1");

		$this->seedDefaults();

		$options = $this->getOrderLineOptions();

		foreach($options as $option){

			$optionObj = OrderLineOption::create($option);
			$optionObj->save();
		}
	}

	protected function seedDefaults(){

		$orders = [];

		$orders[] = Order::where('sidemark', '=', 'Ratna/Girls')->firstOrFail();
		$orders[] = Order::where('sidemark', '=', 'Punzi/Klausner/Olivia')->firstOrFail();
		$orders[] = Order::where('sidemark', '=', 'Corcoran/Cotter/Nook')->firstOrFail();
		$orders[] = Order::where('sidemark', '=', 'Corcoran/Cotter/Mom\'s Bath')->firstOrFail();
		$orders[] = Order::where('sidemark', '=', 'JRD/Presidio/DR')->firstOrFail();
		$orders[] = Order::where('sidemark', '=', 'Dreiling/MBR')->firstOrFail();

		foreach($orders as $order){
			foreach($order->orderlines as $orderLine){

				$orderLineOptions = $order->getNewOrderLineOptions();

				foreach($orderLineOptions as $orderLineOption){

					unset($orderLineOption['data']);

					$orderLineOption->order_line_id = $orderLine->id;
					$orderLineOption->save();

					if($orderLineOption->dataUnsaved){

					    $orderLineOption->dataUnsaved->order_line_option_id = $orderLineOption->id;
					    $orderLineOption->dataUnsaved->save();
					}
				}
			}
		}
	}

	protected function getOrderLineOptions(){

		$options = [];

	    return $options;
	}
}