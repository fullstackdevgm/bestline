<?php

namespace Api;

use \Response;
use \Input;
use \FormulaInterpreter\Executable;
use \Log;
use \Fabric\Type;
use \Fabric;
use \Company;
use \Option;
use \PricingType;
use \Exception;
use \Inventory\Fabric as FabricInventory;
use \Validator;

class FabricController extends BaseController
{

    public function getType($fabric_type_id)
    {
        $type = Type::with(array('fabrics' => function($q){
            $q->whereNull('owner_company_id');
        }))->where('id', '=', $fabric_type_id)->first();

        foreach($type->fabrics as $fabric){
            $fabric->name = $fabric->getNameAttribute();
        }
        return \Response::json($type->fabrics);
    }

    public function getTypes(){
        $types = Type::all();
        return Response::json($types);
    }

    public function getIndex($id = null)
    {
        $fabric = \Fabric::find($id);

        if(!$fabric instanceof \Fabric) {
            if($id === 'null'){
                $fabric = new Fabric;
            } else {
                Log::error("Could not locate fabric with id $id");
                return Response::json("Fabric Not Found", 404);
            }
        }

        $fabric->name = $fabric->name;
        $fabric->inventory = $fabric->inventory;
        $fabric->all_inventory = $fabric->all_inventory;
        $typeIds = [];
        foreach($fabric->types as $type){
            $typeIds[] = $type->id;
        }
        $fabric->type_ids = $typeIds;
        $fabric->select_options = new \stdClass();
        $fabric->select_options->fabric_types = Type::all()->lists('name', 'id');
        $fabric->select_options->options = Option::whereNull('parent_id')->lists('name', 'id');
        $fabric->select_options->pricing_types = PricingType::getTypes();
        $fabric->select_options->companies = Company::all()->lists('name', 'id');
        $fabric->select_options->grades = $fabric->gradeOptions();

        return Response::json($fabric);
    }

    public function getCalculatePrice($fabricId)
    {
        $width = Input::get('width');
        $height = Input::get('height');

        if(is_null($width) || is_null($height)) {
            Log::error("Invalid width and height provided to calculate price");
            return Response::json("Invalid Input", 500);
        }

        $fabric = \Fabric::find($fabricId);

        if(!$fabric instanceof \Fabric) {
            Log::error("Could not locate fabric with id $fabricId to calculate price");
            return Response::json("Invalid Fabric ID", 404);
        }

        $price = Input::get('price', $fabric->unit_price);

        $pricingType = $fabric->getPricingType();

        if(!$pricingType instanceof \PricingType) {
            Log::error("Could not locate pricing type for fabric ID {$fabric->id}");
            return Response::json("Could not locate pricing Type", 500);
        }

        $pricingFormula = $pricingType->getFormulaExecutor();

        if(!$pricingFormula instanceof Executable) {
            Log::error("Could not get pricing formula for pricing type ID {$pricingType->id}");
            return Response::json("Could not get pricing formula", 500);
        }

        $width = $this->calculateFabricMultiple($width);

        $inputs = array(
            'width' => $width,
            'height' => $height,
            'length' => $height,
            'return' => 0,
            'unit_price' => $price,
            'markup_percentage' => 1
        );

        $price = $pricingFormula->run($inputs);

        $retval = array(
            'formula' => $pricingType->formula,
            'inputs' => $inputs,
            'result' => $price
        );

        return Response::json($retval);
    }

    protected function calculateFabricMultiple($inputWidth)
    {
        $fabricWidth = $this->width;

        if($fabricWidth >= $inputWidth) {
            return 1;
        }

        // Input width is greater than one fabric sheet

        return ceil($inputWidth / $fabricWidth);
    }

    public function getAll(){

        $fabrics = Fabric::orderBy('created_at', 'DESC')->get();

        $this->loadFabricAttributes($fabrics);

        return Response::json($fabrics);
    }

    public function getBestline(){

        $fabrics = Fabric::whereNull('owner_company_id')->orderBy('created_at', 'DESC')->get();

        $this->loadFabricAttributes($fabrics);

        return Response::json($fabrics);
    }

    public function getCom(){

        $fabrics = Fabric::whereNotNull('owner_company_id')->orderBy('created_at', 'DESC')->get();

        $this->loadFabricAttributes($fabrics);

        return Response::json($fabrics);
    }

    public function getUnknown(){

        $unknownCompany = Company::where('name', '=', 'Unknown')->first();
        $foundCompany = $unknownCompany instanceof Company;
        if(!$foundCompany){
            return Response::json(array('error' => array('message' => 'Unknown Company Not Found')), 422);
        }

        $fabrics = Fabric::where('owner_company_id', '=', $unknownCompany->id)->orderBy('created_at', 'DESC')->get();

        $this->loadFabricAttributes($fabrics);

        return Response::json($fabrics);
    }

    protected function loadFabricAttributes($fabrics){
        foreach($fabrics as $fabric){
            $fabric->name = $fabric->name;
            $fabric->quantity = $fabric->quantity;
            $fabric->owner_company = $fabric->owner_company;
            $fabric->sidemark = ($fabric->sidemark)? $fabric->sidemark : "";
        }
    }

    public function saveFabric($fabricId){

        $inputs = Input::all();

        try {

            $fabric = Fabric::createFabricFromInputs($inputs);

            $fabric->save();

            //set fabric type
            $fabricTypeIds = isset($inputs['type_ids']) ? $inputs['type_ids'] : NULL;
            $fabric->types()->detach();
            if($fabricTypeIds){
                $fabric->types()->attach($fabricTypeIds);
            }

            $quantity = isset($inputs['quantity']) ? $inputs['quantity'] : 0;
            if(!isset($inputs['id']) && $quantity > 0) {

                $adjustment = new FabricInventory();
                $adjustment->fabric_id = $fabric->id;
                $adjustment->adjustment = $quantity;
                $adjustment->quantity = $quantity;
                $adjustment->by_user_id = \Auth::user()->id;
                $adjustment->reason = "Initial Addition";
                $adjustment->save();
            }

        } catch(Exception $e) {
            return Response::json(array('error' => array('message' => $e->getMessage(), 'trace' => $e->getTrace())), 422);
        }

        return Response::json($fabric);
    }
}