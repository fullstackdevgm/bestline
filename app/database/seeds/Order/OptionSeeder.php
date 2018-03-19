<?php

namespace OrderSeeder;

use \Order;
use \Option;
use \Order\Option as OrderOption;
use \Eloquent;
use \DB;
use \DatabaseSeeder;

class OptionSeeder extends DatabaseSeeder
{
	public function run()
	{
		Eloquent::unguard();

		DB::unprepared("SET foreign_key_checks=0");
		OrderOption::truncate();
		DB::unprepared("SET foreign_key_checks=1");

		$orderOptions = $this->getOrderOptions();

		foreach($orderOptions as $option){

            $data = (isset($option['data']))? $option['data'] : null;
            unset($option['data']);

			$optionObj = OrderOption::create($option);
			$optionObj->save();

            if($data){
                $data['order_option_id'] = $optionObj->id;
                $optionObj->data()->create($data);
                $optionObj->data->save();
            }
		}
	}

	protected function getOrderOptions(){

		$orderOptions = [];

		//setup Punzi/Klausner/Olivia order options
		$order = Order::where('sidemark', '=', 'Punzi/Klausner/Olivia')->firstOrFail();
		$orderOptions[] = array(
            'order_id' => $order->id,
            'option_id' => Option::where('name', '=', 'Headerboard')->firstOrFail()->id,
            'sub_option_id' => Option::where('name', '=', 'Headerboard (sub)')->firstOrFail()->id,
        );
		$orderOptions[] = array(
            'order_id' => $order->id,
            'option_id' => Option::where('name', '=', 'Continuous Cord')->firstOrFail()->id,
            'sub_option_id' => Option::where('name', '=', 'Continuous Cord (sub)')->firstOrFail()->id,
        );
		$orderOptions[] = array(
            'order_id' => $order->id,
            'option_id' => Option::where('name', '=', 'Cording')->firstOrFail()->id,
            'sub_option_id' => Option::where('name', '=', 'Cording - Sides, Bottom')->firstOrFail()->id,
        );
		$orderOptions[] = array(
            'order_id' => $order->id,
            'option_id' => Option::where('name', '=', 'Valance Application')->firstOrFail()->id,
            'sub_option_id' => Option::where('name', '=', 'Cord Forward Valance')->firstOrFail()->id,
        );

        //setup Corcoran/Cotter/Nook order options
        $order = Order::where('sidemark', '=', 'Corcoran/Cotter/Nook')->firstOrFail();
        $orderOptions[] = array(
            'order_id' => $order->id,
            'option_id' => Option::where('name', '=', 'Continuous Cord')->firstOrFail()->id,
            'sub_option_id' => Option::where('name', '=', 'Continuous Cord')->firstOrFail()->id,
        );
        $orderOptions[] = array(
            'order_id' => $order->id,
            'option_id' => Option::where('name', '=', 'Side Tabs')->firstOrFail()->id,
            'sub_option_id' => Option::where('name', '=', 'Side Tabs (Pr.) - Up to 7" drop')->firstOrFail()->id,
        );

        //setup Corcoran/Cotter/Mom\'s Bath order options
        $order = Order::where('sidemark', '=', 'Corcoran/Cotter/Mom\'s Bath')->firstOrFail();
        $orderOptions[] = array(
            'order_id' => $order->id,
            'option_id' => Option::where('name', '=', 'Side Tabs')->firstOrFail()->id,
            'sub_option_id' => Option::where('name', '=', 'Side Tabs (Pr.) - Up to 7" drop')->firstOrFail()->id,
        );

	    return $orderOptions;
	}
}