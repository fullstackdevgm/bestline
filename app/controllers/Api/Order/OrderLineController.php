<?php

namespace Api\Order;

use \Log;
use \Response;
use \Order;
use \Order\Option as OrderOption;
use \Input;
use \Order\LineItem as OrderLine;
use \Order\LineItem\Option as OrderLineOption;
use \Bestline\Order\Calculator;
use \Eloquent\Model;
use \Exception;
use \Lookups\PullType;
use \Lookups\CordPosition;
use \Lookups\Hardware;
use \Lookups\Mount;
use \User;

class OrderLineController extends \Api\BaseController
{   

    public function getNew($orderId)
    {
    	$order = Order::find($orderId);
    	
    	if(!$order instanceof Order) {
    	    Response::json(array('success' => false, 'message' => 'Could Not Find Order', 'trace' => null), 404);
    	}

		$newOrderLine = array(
			'order_id' => $order->id,
            'product_id' => $order->product->id,
            'hardware_id' => Hardware::where('description', '=', 'Standard')->firstOrFail()->id,
            'cord_position_id' => CordPosition::where('description', '=', 'Left')->firstOrFail()->id,
            'cord_length' => 0,
            'pull_type_id' => PullType::where('description', '=', 'White Tassel')->firstOrFail()->id,
            'return' => 0.000,
            'valance_width' => 0,
            'valance_height' => 0,
            'valance_return' => 0,
            'valance_headerboard' => 0,
        );

    	try{
    		$orderLine = OrderLine::getLineItemFromInputs($newOrderLine);
    		$orderLineOptions = $order->getNewOrderLineOptions();
    		foreach($orderLineOptions as $option){
    			$orderLine->options[] = $option->toArray();
    		}
    	} catch(Exception $e) {
            return Response::json(array('success' => false, 'message' => $e->getMessage(), 'trace' => $e->getTrace()), 422);
        }

    	return Response::json($orderLine);
    }

    public function calculate(){

    	$data = Input::all();

		$orderLine = OrderLine::getLineItemFromInputs($data);
		
		//temporarily delete all existing options
		$optionCnt = 0;
		foreach($orderLine->options as $option){
			unset($orderLine->options[$optionCnt]);
			$optionCnt++;
		}

		//add most updated options
	    if(isset($data['options']) && is_array($data['options'])) {
	        foreach($data['options'] as $lineOption) {
	            $orderLine->options[] = OrderLineOption::getFromInputs($lineOption);
	        }
	    }

	    $calculations = Calculator::calculateOrderLine($orderLine);
        
      	return Response::json($calculations);
    }

    public function delete($order_id, $order_line_id){

    	$orderLineToDelete = OrderLine::findOrFail($order_line_id);
    	foreach($orderLineToDelete->order->orderLines as $orderLine){
			$orderLine->line_number--;
			$orderLine->save();
		}

    	$orderLineToDelete->delete();
    	  
    	return Response::json($order_line_id ." Deleted");
    }
}
