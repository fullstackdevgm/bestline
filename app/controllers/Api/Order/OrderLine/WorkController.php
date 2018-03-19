<?php

namespace Api\Order\OrderLine;

use \Log;
use \Response;
use \Order\LineItem as OrderLine;
use \Order\LineItem\Work as OrderLineWork;
use \User;
use \Station;
use \DB;
use \Input;

class WorkController extends \Api\BaseController
{
    public function checkout($order_id, $order_line_id, $work_id, $user_id){ 
        
        $inputs = Input::get('data', '{}');

        $orderLine = OrderLine::find($order_line_id);
        $hasOrder = $orderLine instanceof OrderLine;
        if(!$hasOrder){
            return Response::json(array('error' => array('message' => 'Could not find the order line with id: '. $order_line_id)), 422);
        }

        $user = User::find($user_id);
        $hasUser = $user instanceof User;
        if(!$hasUser){
            return Response::json(array('error' => array('message' => 'Could not find the user with id: '. $user_id)), 422);
        }

        if($orderLine->current_work){
            return Response::json(array('error' => array('message' => 'This order line #'. $orderLine->id .' is already checked out')), 422);
        }

        $newWork = [
            'order_id' => $orderLine->order->id,
            'order_line_id' => $orderLine->id,
            'station_id' => $orderLine->current_station_id,
            'user_id' => $user->id,
            'start_time' => date('Y-m-d H:i:s'),
        ];

        try{
            $orderLine->currentWork()->create($newWork);
        } catch(Exception $e) {
            return Response::json(array('error' => array('message' => $e->getMessage(), 'trace' => $e->getTrace())), 422);
        }

        $orderLine->currentWork->user = $orderLine->currentWork->user;

        return Response::json($orderLine->currentWork);
    }

    public function undoCheckout($order_id, $order_line_id, $work_id){
        $work = OrderLineWork::find($work_id);
        $hasWork = $work instanceof OrderLineWork;
        if(!$hasWork){
            return Response::json(array('error' => array('message' => 'Could not find work for order line #'. $order_line_id)), 422);
        }

        try{
            $work->delete();
        } catch(\Exception $e) {
            return Response::json(array('error' => array('message' => $e->getMessage(), 'trace' => $e->getTrace())), 422);
        }

        return Response::json(null);
    }

    public function checkin($order_id, $order_line_id, $work_id){

    	$work = OrderLineWork::find($work_id);
    	$hasWork = $work instanceof OrderLineWork;
    	if(!$hasWork){
    		return Response::json(array('error' => array('message' => 'Could not find work for order line #'. $order_line_id)), 422);
    	}

        //find next station
    	$currentStation = $work->orderLine->currentStation;
        $nextStationId = Station::where('sort_order', '>', $currentStation->sort_order)->orderBy('sort_order', 'ASC')->first()->id;

        try{
            DB::beginTransaction();

            //change the order line's station
            $work->orderLine->current_station_id = $nextStationId;
            $work->orderLine->save();

            //save the completed time
            $work->complete_time = date('Y-m-d H:i:s');
            $work->save();

            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
            return Response::json(array('error' => array('message' => $e->getMessage(), 'trace' => $e->getTrace())), 422);
        }

        //change the order's station if all order lines are changed
        $orderLinesAtThisStage = [];
        foreach($work->order->order_lines as $orderLine){
            if($orderLine->current_station_id === $currentStation->id){
                $orderLinesAtThisStage[] = $orderLine->id;
            }
        }
        $moveParentOrder = count($orderLinesAtThisStage) === 0;
        if($moveParentOrder){
            $work->order->current_station_id = $nextStationId;
            $work->order->save();
        }

        $work->user = $work->user;
        
      	return Response::json($work);
    }
}
