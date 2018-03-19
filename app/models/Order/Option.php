<?php

namespace Order;

use \Log;
use \Validator;
use \Exception;
use \Eloquent;
use \Option as BaseOption;
use \Option\Data as OptionData;

class Option extends Eloquent
{
    protected $table = "order_default_options";
    public $dataUnsaved = null;
    
    //setup relationships
    public function option()
    {
        return $this->hasOne('Option', 'id', 'option_id');
    }

    public function subOption()
    {
        return $this->hasOne('Option', 'id', 'sub_option_id');
    }

    public function data(){
        return $this->hasOne('Option\Data', 'order_option_id', 'id');
    }

    //other functions
    public static function getFromInputs($inputs)
    {
        //validate input
        $validator = Validator::make($inputs, static::getValidationRules());
        if($validator->fails()) {throw new Exception('An option is not formatted correctly. Error:' . $validator->messages()->first());}

        //get order option object
    	if(isset($inputs['id'])){
    		$option = static::find($inputs['id']);

    		if(!$option instanceof static) {
    		    $option = new static;
    		}
    	} else {
    		$option = new static;
    	}

        //check that option ids are valid
        $baseOption = BaseOption::find($inputs['option_id']);
        $hasOption = $baseOption instanceof BaseOption;
        $subOption = BaseOption::find($inputs['sub_option_id']);
        $hasSubOption = $subOption instanceof BaseOption;

        if(!$hasOption || !$hasSubOption) {
            throw new Exception("Failed to locate option");
        }

        $option->option_id = $inputs['option_id'];
        $option->sub_option_id = $inputs['sub_option_id'];

        //set price if it was passed
        $inputHasPrice = isset($inputs['price']) && $inputs['price'] !== "";
        if($inputHasPrice){
            $option->price = $inputs['price'];
        }

        $inputHasOrderFabricOptionId = isset($inputs['order_fabric_option_id']) && $inputs['order_fabric_option_id'] !== "";
        if($inputHasOrderFabricOptionId){
            $option->order_fabric_option_id = $inputs['order_fabric_option_id'];
        }

        $inputHasOrderFabric = isset($inputs['order_fabric_id']) && $inputs['order_fabric_id'] !== "";
        if($inputHasOrderFabric){
            $option->order_fabric_id = $inputs['order_fabric_id'];
        }

        $inputHasOrderOptionId = isset($inputs['order_option_id']) && $inputs['order_option_id'] !== "";
        if($inputHasOrderOptionId){
            $option->order_option_id = $inputs['order_option_id'];
        }

        $inputHasData = isset($inputs['data']);
        if($inputHasData){
            $option->dataUnsaved = OptionData::getFromInputs($inputs['data']);
        }

        return $option;
    }

    public static function getValidationRules(){
        return array(
            'option_id' => 'required',
            'sub_option_id' => 'required',
        );
    }
}