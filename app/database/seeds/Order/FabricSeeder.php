<?php

namespace OrderSeeder;

use \Order\Fabric as OrderFabric;
use \Eloquent;
use \DB;
use \DatabaseSeeder;
use \Fabric\Type as FabricType;
use \Order;
use \Fabric;

class FabricSeeder extends DatabaseSeeder
{
	public function run()
	{
		Eloquent::unguard();

		DB::unprepared("SET foreign_key_checks=0");
		OrderFabric::truncate();
		DB::unprepared("SET foreign_key_checks=1");

		$orderFabrics = $this->getOrderFabrics();

		foreach($orderFabrics as $fabric){

			$fabricObj = OrderFabric::create($fabric);
			$fabricObj->save();
		}
	}

	protected function getOrderFabrics(){
		$orderFabrics = [];

		$fabricTypeFace = FabricType::where('type', '=', 'face')->firstOrFail();
		$fabricTypeLining = FabricType::where('type', '=', 'lining')->firstOrFail();
		$fabricTypeEmbellishment = FabricType::where('type', '=', 'embellishment')->firstOrFail();

		//setup Ratna/Girls fabrics
		$order = Order::where('sidemark', '=', 'Ratna/Girls')->firstOrFail();
		$musaBlue = Fabric::where('sidemark', '=', 'Ratna/Girls')->where('pattern', '=', 'Mussa')->firstOrFail();
		$ratnaPlainWhite = Fabric::where('sidemark', '=', 'Ratna/Girls')->where('pattern', '=', 'Plain')->firstOrFail();
		$whiteLining = Fabric::where('pattern', '=', 'White Lining')->where('color', '=', 'White')->firstOrFail();
		$orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => $musaBlue->id,
            'fabric_type_id' => $fabricTypeFace->id,
        );
		$orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => $ratnaPlainWhite->id,
            'fabric_type_id' => $fabricTypeEmbellishment->id,
        );
		$orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => $whiteLining->id,
            'fabric_type_id' => $fabricTypeLining->id,
        );

		//setup Noolas/SCAS fabrics
		$order = Order::where('sidemark', '=', 'Noolas/SCAS')->firstOrFail();
		$orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => Fabric::where('pattern', '=', 'Aurora')->firstOrFail()->id,
            'fabric_type_id' => $fabricTypeFace->id,
        );
		$orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => Fabric::where('pattern', '=', 'White Lining')->firstOrFail()->id,
            'fabric_type_id' => $fabricTypeLining->id,
        );

        // Alice/Tracy Rm 1 fabrics
        $order = Order::where('sidemark', '=', 'Alice/Tracy Rm 1')->firstOrFail();
		$orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => Fabric::where('sidemark', '=', 'Alice/Tracy Rm 1')->where('pattern', '=', 'Dulce Ensign')->firstOrFail()->id,
            'fabric_type_id' => $fabricTypeFace->id,
        );
		$orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => Fabric::where('pattern', '=', 'Stock')->where('color', '=', 'White')->firstOrFail()->id,
            'fabric_type_id' => $fabricTypeLining->id,
        );

        //setup Punzi/Klausner/Olivia fabrics
        $order = Order::where('sidemark', '=', 'Punzi/Klausner/Olivia')->firstOrFail();
		$orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => Fabric::where('pattern', '=', 'Dogwood')->where('color', '=', 'Zebra')->firstOrFail()->id,
            'fabric_type_id' => $fabricTypeFace->id,
        );
		$orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => Fabric::where('pattern', '=', 'Blackout')->where('color', '=', 'White')->firstOrFail()->id,
            'fabric_type_id' => $fabricTypeLining->id,
        );

        //setup Corcoran/Cotter/Nook fabrics
        $order = Order::where('sidemark', '=', 'Corcoran/Cotter/Nook')->firstOrFail();
        $orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => Fabric::where('pattern', '=', 'Biscayne')->where('color', '=', 'Eggshell')->firstOrFail()->id,
            'fabric_type_id' => $fabricTypeFace->id,
        );
        $orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => Fabric::where('pattern', '=', 'White Lining')->firstOrFail()->id,
            'fabric_type_id' => $fabricTypeLining->id,
        );
        $orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => Fabric::where('pattern', '=', 'Ocean')->where('color', '=', 'Blue')->firstOrFail()->id,
            'fabric_type_id' => $fabricTypeEmbellishment->id,
        );

        //setup Corcoran/Cotter/Mom\'s Bath fabrics
        $order = Order::where('sidemark', '=', 'Corcoran/Cotter/Mom\'s Bath')->firstOrFail();
        $orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => Fabric::where('pattern', '=', 'Biscayne')->where('color', '=', 'Eggshell')->firstOrFail()->id,
            'fabric_type_id' => $fabricTypeFace->id,
        );
        $orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => Fabric::where('pattern', '=', 'Ocean')->where('color', '=', 'Green')->firstOrFail()->id,
            'fabric_type_id' => $fabricTypeEmbellishment->id,
        );

        //setup Foster/K fabrics
        $order = Order::where('sidemark', '=', 'Foster/K')->firstOrFail();
        $orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => Fabric::where('pattern', '=', 'Aurora')->where('color', '=', 'Navy')->firstOrFail()->id,
            'fabric_type_id' => $fabricTypeFace->id,
        );
        $orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => Fabric::where('pattern', '=', 'White Lining')->where('color', '=', 'White')->firstOrFail()->id,
            'fabric_type_id' => $fabricTypeLining->id,
        );

        //setup JRD/Presidio/DR fabrics
        $order = Order::where('sidemark', '=', 'JRD/Presidio/DR')->firstOrFail();
        $orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => Fabric::where('pattern', '=', 'Cronus')->where('color', '=', 'Natural')->firstOrFail()->id,
            'fabric_type_id' => $fabricTypeFace->id,
        );
        $orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => Fabric::where('pattern', '=', 'Stock')->where('color', '=', 'White')->firstOrFail()->id,
            'fabric_type_id' => $fabricTypeLining->id,
        );
        $orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => Fabric::where('pattern', '=', 'Ocean')->where('color', '=', 'Green')->firstOrFail()->id,
            'fabric_type_id' => $fabricTypeEmbellishment->id,
        );

        //setup Hansen/Remake fabrics
        $order = Order::where('sidemark', '=', 'Hansen/Remake')->firstOrFail();
        $orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => Fabric::where('pattern', '=', 'Birch')->where('color', '=', 'Carmel')->firstOrFail()->id,
            'fabric_type_id' => $fabricTypeFace->id,
        );
        $orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => Fabric::where('pattern', '=', 'Stock')->where('color', '=', 'White')->firstOrFail()->id,
            'fabric_type_id' => $fabricTypeLining->id,
        );

        //setup Dreiling/MBR
        $order = Order::where('sidemark', '=', 'Dreiling/MBR')->firstOrFail();
        $orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => Fabric::where('sidemark', '=', 'Acme Fabric')->where('color', '=', 'Grey')->firstOrFail()->id,
            'fabric_type_id' => $fabricTypeFace->id,
        );
        $orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => Fabric::where('sidemark', '=', 'Acme Embellishment')->where('color', '=', 'Blue')->firstOrFail()->id,
            'fabric_type_id' => $fabricTypeEmbellishment->id,
        );
        $orderFabrics[] = array(
            'order_id' => $order->id,
            'fabric_id' => Fabric::where('pattern', '=', 'Stock')->where('color', '=', 'White')->firstOrFail()->id,
            'fabric_type_id' => $fabricTypeLining->id,
        );

	    return $orderFabrics;
	}
}