<?php

namespace Api;

use \Response;
use \Product;
use \Log;
use \Order;
use \User;

class StationController extends BaseController
{
    public function getOrders($stationId)
    {
        $stationOrders = Order::with(
            'orderLines',
            'orderLines.order',
            'company',
            'product'
        )->where('current_station_id', '=', $stationId)->orderBy('id', 'desc')->get();

        //load other properties that can't be eager loaded
        foreach($stationOrders as $stationOrder){
            foreach($stationOrder->order_lines as $orderLine){
                $orderLine->station_id = $stationId;
                $orderLine->current_work = $orderLine->current_work; //load current work
                if($orderLine->current_work){
                    $orderLine->current_work->user = $orderLine->current_work->user;
                }
                $orderLine->order = $orderLine->order; //load order because it is undefined for some reason
            }
        }

        return Response::json($stationOrders);
    }

    public function getUsers($stationId)
    {
        $stationUsers = User::where('station_id', '=', $stationId)->orderBy('id', 'desc')->get();
        return Response::json($stationUsers);
    }
}
