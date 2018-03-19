<?php

namespace OrderSeeder;

use \Order\LineItem as OrderLine;
use \Eloquent;
use \DB;
use \DatabaseSeeder;
use \Lookups\PullType;
use \Lookups\CordPosition;
use \Lookups\Hardware;
use \Lookups\Mount;
use \Lookups\ValanceType;
use \Product;
use \Order;
use \Log;
use \stdClass;

class OrderLineSeeder extends DatabaseSeeder
{
	public function run()
	{
		Eloquent::unguard();

		DB::unprepared("SET foreign_key_checks=0");
		OrderLine::truncate();
        DB::table('order_line_work')->truncate();
		DB::unprepared("SET foreign_key_checks=1");

		$orderLines = $this->getOrderLines();

		foreach($orderLines as $orderLine){

			$orderLineObj = OrderLine::getLineItemFromInputs($orderLine);
			$orderLineObj->save();
		}
	}
	protected function getOrderLines(){

		$orderLines = [];

		//setup reusable Ids
		$mounts = new stdClass();
		$mounts->IB = Mount::where('description', '=', 'IB')->firstOrFail();
		$mounts->OB = Mount::where('description', '=', 'OB')->firstOrFail();

		$hardware = new stdClass();
		$hardware->CordLock = Hardware::where('description', '=', 'Cord Lock')->firstOrFail();
        $hardware->ContinuousCord = Hardware::where('description', '=', 'Continuous Cord')->firstOrFail();
        $hardware->Standard = Hardware::where('description', '=', 'Standard')->firstOrFail();

		$cordPosition = new stdClass();
		$cordPosition->Right =CordPosition::where('description', '=', 'Right')->firstOrFail();
        $cordPosition->Left =CordPosition::where('description', '=', 'Left')->firstOrFail();
        $cordPosition->LeftRight =CordPosition::where('description', '=', 'Left/Right')->firstOrFail();

		$pullType = new stdClass();
		$pullType->WhiteCloth = PullType::where('description', '=', 'White Cloth')->firstOrFail();
        $pullType->WhitePlastic = PullType::where('description', '=', 'White Plastic')->firstOrFail();
        $pullType->WhiteTassel = PullType::where('description', '=', 'White Tassel')->firstOrFail();
        $pullType->BoneTassel = PullType::where('description', '=', 'Bone Tassel')->firstOrFail();

        $adjustment = new stdClass();
        $adjustment->HeightToRodId = OrderLine::getHeightAdjustmentIdByName('Height to Rod');

        $valanceType = new stdClass();
        $valanceType->BoxPleated = ValanceType::where('type', '=', 'slug_box_pleated')->firstOrFail();

		//setup Ratna/Girls orderlines
		$order = Order::where('sidemark', '=', 'Ratna/Girls')->firstOrFail();
		$orderLines[] = array(
            'cord_length' => 23,
            'product_id' => $order->product->id,
            'pull_type_id' => 1,
            'cord_position_id' => 3,
            'hardware_id' => 2,
            'mount_id' => $mounts->IB->id,
            'order_id' => $order->id,
            'width' => 62.125,
            'height' => 45.5,
            'headerboard' => 1.5,
            'return' => 0,
        );
        $orderLines[] = array(
            'cord_length' => 23,
            'product_id' => $order->product->id,
            'pull_type_id' => 1,
            'cord_position_id' => 3,
            'hardware_id' => 4,
            'mount_id' => $mounts->IB->id,
            'order_id' => $order->id,
            'width' => 27,
            'height' => 45.5,
            'headerboard' => 1.5,
            'return' => 0,
        );

	    //add Noolas/SCAS orderlines
	    $order = Order::where('sidemark', '=', 'Noolas/SCAS')->firstOrFail();
	    $orderLines[] = array(
            'cord_length' => 23,
            'product_id' => $order->product->id,
            'pull_type_id' => $pullType->WhiteCloth->id,
            'cord_position_id' => $cordPosition->Right->id,
            'hardware_id' => $hardware->CordLock->id,
            'mount_id' => $mounts->IB->id,
            'order_id' => $order->id,
            'width' => 49.750,
            'height' => 87,
            'headerboard' => 1.5,
            'return' => 0,
            'height_adjustment_option_id' => $adjustment->HeightToRodId,
        );

        //add Alice/Tracy orderlines
        $order = Order::where('sidemark', '=', 'Alice/Tracy Rm 1')->firstOrFail();
        $orderLines[] = array(
            'cord_length' => 24,
            'product_id' => $order->product->id,
            'pull_type_id' => $pullType->WhiteCloth->id,
            'cord_position_id' => $cordPosition->Right->id,
            'hardware_id' => $hardware->CordLock->id,
            'mount_id' => $mounts->OB->id,
            'order_id' => $order->id,
            'width' => 78,
            'height' => 48,
            'headerboard' => 1.5,
            'return' => 0,
        );

        //seed Punzi/Klausner/Olivia orderlines
        $order = Order::where('sidemark', '=', 'Punzi/Klausner/Olivia')->firstOrFail();
        $orderLines[] = array(
            'cord_length' => 36,
            'product_id' => $order->product->id,
            'pull_type_id' => $pullType->WhitePlastic->id,
            'cord_position_id' => $cordPosition->Left->id,
            'hardware_id' => $hardware->ContinuousCord->id,
            'mount_id' => $mounts->OB->id,
            'order_id' => $order->id,
            'width' => 30,
            'height' => 75,
            'headerboard' => 2.5,
            'return' => 0,
            'has_valance' => true,
            'valance_type_id'=> $valanceType->BoxPleated->id,
            'valance_width' => 30.75,
            'valance_height' => 6,
            'valance_return' => 2.5,
        );
        $orderLines[] = array(
            'cord_length' => 36,
            'product_id' => $order->product->id,
            'pull_type_id' => $pullType->WhitePlastic->id,
            'cord_position_id' => $cordPosition->Right->id,
            'hardware_id' => $hardware->ContinuousCord->id,
            'mount_id' => $mounts->OB->id,
            'order_id' => $order->id,
            'width' => 30,
            'height' => 75,
            'headerboard' => 2.5,
            'return' => 0,
            'has_valance' => true,
            'valance_type_id'=> $valanceType->BoxPleated->id,
            'valance_width' => 30.75,
            'valance_height' => 6,
            'valance_return' => 2.5,
        );

        //seed Foster/K orderlines
        $order = Order::where('sidemark', '=', 'Foster/K')->firstOrFail();
        $orderLines[] = array(
            'cord_length' => 32,
            'product_id' => $order->product->id,
            'pull_type_id' => $pullType->BoneTassel->id,
            'cord_position_id' => $cordPosition->LeftRight->id,
            'hardware_id' => $hardware->CordLock->id,
            'mount_id' => $mounts->OB->id,
            'order_id' => $order->id,
            'width' => 78,
            'height' => 48,
            'headerboard' => 1.250,
            'return' => 0.000,
        );

        //seed Corcoran/Cotter/Nook orderlines
        $order = Order::where('sidemark', '=', 'Corcoran/Cotter/Nook')->firstOrFail();
        $orderLines[] = array(
            'cord_length' => 36,
            'product_id' => $order->product->id,
            'pull_type_id' => $pullType->WhitePlastic->id,
            'cord_position_id' => $cordPosition->Left->id,
            'hardware_id' => $hardware->ContinuousCord->id,
            'mount_id' => $mounts->OB->id,
            'order_id' => $order->id,
            'width' => 74,
            'height' => 52.750,
            'headerboard' => 1.5,
            'return' => 0.000,
        );
        $orderLines[] = array(
            'cord_length' => 36,
            'product_id' => $order->product->id,
            'pull_type_id' => $pullType->WhitePlastic->id,
            'cord_position_id' => $cordPosition->Right->id,
            'hardware_id' => $hardware->ContinuousCord->id,
            'mount_id' => $mounts->OB->id,
            'order_id' => $order->id,
            'width' => 70.250,
            'height' => 53,
            'headerboard' => 1.5,
            'return' => 0.000,
        );

        //seed Corcoran/Cotter/Mom\'s Bath orderlines
        $order = Order::where('sidemark', '=', 'Corcoran/Cotter/Mom\'s Bath')->firstOrFail();
        $orderLines[] = array(
            'cord_length' => 36,
            'product_id' => $order->product->id,
            'pull_type_id' => $pullType->WhiteTassel->id,
            'cord_position_id' => $cordPosition->Right->id,
            'hardware_id' => $hardware->Standard->id,
            'mount_id' => $mounts->OB->id,
            'order_id' => $order->id,
            'width' => 34,
            'height' => 48,
            'headerboard' => 1.5,
            'return' => 0.000,
        );

        //seed JRD/Presidio/DR orderlines
        $order = Order::where('sidemark', '=', 'JRD/Presidio/DR')->firstOrFail();
        $orderLines[] = array(
            'cord_length' => 32,
            'product_id' => $order->product->id,
            'pull_type_id' => $pullType->BoneTassel->id,
            'cord_position_id' => $cordPosition->Left->id,
            'hardware_id' => $hardware->Standard->id,
            'mount_id' => $mounts->IB->id,
            'order_id' => $order->id,
            'width' => 30.25,
            'height' => 64.25,
            'headerboard' => 1.5,
            'return' => 0.000,
        );
        $orderLines[] = array(
            'cord_length' => 32,
            'product_id' => $order->product->id,
            'pull_type_id' => $pullType->BoneTassel->id,
            'cord_position_id' => $cordPosition->Right->id,
            'hardware_id' => $hardware->Standard->id,
            'mount_id' => $mounts->IB->id,
            'order_id' => $order->id,
            'width' => 34.75,
            'height' => 64.25,
            'headerboard' => 1.5,
            'return' => 0.000,
        );
        $orderLines[] = array(
            'cord_length' => 32,
            'product_id' => $order->product->id,
            'pull_type_id' => $pullType->BoneTassel->id,
            'cord_position_id' => $cordPosition->Right->id,
            'hardware_id' => $hardware->Standard->id,
            'mount_id' => $mounts->IB->id,
            'order_id' => $order->id,
            'width' => 30.25,
            'height' => 64.25,
            'headerboard' => 1.5,
            'return' => 0.000,
        );

        //seed Hansen/Remake orderlines
        $order = Order::where('sidemark', '=', 'Hansen/Remake')->firstOrFail();
        $orderLines[] = array(
            'cord_length' => 26,
            'product_id' => $order->product->id,
            'pull_type_id' => $pullType->WhiteTassel->id,
            'cord_position_id' => $cordPosition->Right->id,
            'hardware_id' => $hardware->CordLock->id,
            'mount_id' => $mounts->OB->id,
            'order_id' => $order->id,
            'width' => 56.250,
            'height' => 53,
            'headerboard' => 1.000,
            'return' => 1.000,
            'height_adjustment_option_id' => $adjustment->HeightToRodId,
        );

        //seed Dreiling/MBR orderlines
        $order = Order::where('sidemark', '=', 'Dreiling/MBR')->firstOrFail();
        $orderLines[] = array(
            'cord_length' => 44,
            'product_id' => $order->product->id,
            'pull_type_id' => $pullType->WhiteTassel->id,
            'cord_position_id' => $cordPosition->Right->id,
            'hardware_id' => $hardware->CordLock->id,
            'mount_id' => $mounts->OB->id,
            'order_id' => $order->id,
            'width' => 33,
            'height' => 58,
            'headerboard' => 1.500,
            'return' => 1.500,
            'height_adjustment_option_id' => $adjustment->HeightToRodId,
        );

        return $orderLines;
	}
}