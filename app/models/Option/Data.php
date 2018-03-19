<?php

namespace Option;

use \Log;

class Data extends \Eloquent
{    
    protected $table = "option_data";

    static function getFromInputs($inputs){

	    //get order option object
	    $hasId = isset($inputs['id']);
	    $isNewOptionData = false;
		if($hasId){
			$optionData = static::find($inputs['id']);

			$optionDataFound = $optionData instanceof static;
			if(!$optionDataFound) {
				$isNewOptionData = true;
			    $optionData = new static;
			}
		} else {
			$isNewOptionData = true;
			$optionData = new static;
		}

		$optionData->size = isset($inputs['size'])?  $inputs['size'] : null;
		$optionData->size_sides = isset($inputs['size_sides'])?  $inputs['size_sides'] : null;
		$optionData->size_bottom = isset($inputs['size_bottom'])?  $inputs['size_bottom'] : null;
		$optionData->size_top = isset($inputs['size_top'])?  $inputs['size_top'] : null;
		$optionData->inset_size_sides = isset($inputs['inset_size_sides'])?  $inputs['inset_size_sides'] : null;
		$optionData->inset_size_bottom = isset($inputs['inset_size_bottom'])?  $inputs['inset_size_bottom'] : null;
		$optionData->inset_size_top = isset($inputs['inset_size_top'])?  $inputs['inset_size_top'] : null;
		$optionData->number = isset($inputs['number'])?  $inputs['number'] : null;
		$optionData->degrees = isset($inputs['degrees'])?  $inputs['degrees'] : null;
		$optionData->assembler_note = isset($inputs['assembler_note'])?  $inputs['assembler_note'] : null;

		if(isset($inputs['option_id']) && $isNewOptionData){
			$optionData->parent_option_id = $inputs['option_id'];
		} else if(isset($inputs['parent_option_id'])) {
			$optionData->parent_option_id = $inputs['parent_option_id'];
		} else {
			$optionData->parent_option_id = null;
		}

		if(isset($inputs['order_option_id']) && $isNewOptionData){
			$optionData->parent_order_option_id = $inputs['order_option_id'];
		} else if(isset($inputs['parent_order_option_id'])) {
			$optionData->parent_order_option_id = $inputs['parent_order_option_id'];
		} else {
			$optionData->parent_order_option_id = null;
		}

		if(isset($inputs['order_fabric_option_id']) && $isNewOptionData){
			$optionData->parent_order_fabric_option_id = $inputs['order_fabric_option_id'];
		} else if(isset($inputs['parent_order_fabric_option_id'])) {
			$optionData->parent_order_fabric_option_id = $inputs['parent_order_fabric_option_id'];
		} else {
			$optionData->parent_order_fabric_option_id = null;
		}

		if(isset($inputs['order_line_option_id']) && $isNewOptionData){
			$optionData->parent_order_line_option_id = $inputs['order_line_option_id'];
		} else if(isset($inputs['parent_order_line_option_id'])) {
			$optionData->parent_order_line_option_id = $inputs['parent_order_line_option_id'];
		} else {
			$optionData->parent_order_line_option_id = null;
		}

	    return $optionData;
    }
}