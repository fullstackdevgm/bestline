<?php

namespace Api\Inventory;

use \Fabric;
use \Inventory\Fabric as InventoryFabric;
use \Response;
use \Validator;
use \Exception;
use \Input;
use \Auth;

class FabricController extends \Api\BaseController
{
    public function adjustFabric($fabricId){

        $fabric = Fabric::find($fabricId);

        if(!$fabric instanceof Fabric) {
            return Response::json(array('error' => array('message' => 'Invalid ID')), 422);
        }

        $validator = Validator::make(\Input::all(), [
            'adjustment' => 'required|integer',
            'reason' =>'required'
        ]);

        if($validator->fails()) {
            return Response::json($validator->messages(), 422);
        }

        try{

            $quantity = $fabric->quantity;

            $newQuantity = $quantity + Input::get('adjustment');

            if($newQuantity < 0) {
                return Response::json(array('error' => array('message' => 'Cannot have a negative available quantity')), 422);
            }

            $adjustment = new InventoryFabric();
            $adjustment->fabric_id = $fabric->id;
            $adjustment->adjustment = (int)Input::get('adjustment');
            $adjustment->quantity = $newQuantity;
            $adjustment->by_user_id = Auth::user()->id;
            $adjustment->reason = Input::get('reason');

            $adjustment->save();

        } catch(Exception $e) {
            return Response::json(array('error' => array('message' => $e->getMessage(), 'trace' => $e->getTrace())), 422);
        }

        return Response::json($adjustment);
    }
}