<?php

namespace OrderSeeder;

use \Order;
use \DatabaseSeeder;
use Bestline\Order\Calculator;

class OrderCalculate extends DatabaseSeeder
{
	public function run()
	{
		$orders = Order::all();

		foreach($orders as $order){

			foreach($order->orderLines as $orderLine){

				Calculator::calculateOrderLine($orderLine);
				$orderLine->save();

				foreach($orderLine->options as $option){
					$option->save();
				}
			}

			$order->calculateTotals();
			$order->save();
		}
	}
}
