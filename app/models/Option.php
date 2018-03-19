<?php

use Baum\Node;

/**
* Option
*/
class Option extends Node
{
    const PRICING_TYPE_SQFT = "sqft";
    const PRICING_TYPE_SQFT_ACTUAL = "sqft_actual";
    const PRICING_TYPE_LINEAR = "linear";
    const PRICING_TYPE_FLAT = "flat";
    const PRICING_TYPE_L2W1 = "l2w1";
    const PRICING_TYPE_W1 = "w1";
    const PRICING_TYPE_L2 = "l2";
    const PRICING_TYPE_L1 = "l1";
    const PRICING_TYPE_PERCENT = "percent";
    const PRICING_TYPE_YARD = "yard";
    const PRICING_TYPE_L2W2 = "l2w2";

   /**
    * Table name.
    *
    * @var string
    */
    protected $table = 'options';

    static public function findBaseOptions()
    {
        return static::roots()->orderBy('name');
    }

    public function getPricingType()
    {
        return PricingType::where('type', '=', $this->pricing_type)->first();
    }

    public function parents()
    {
        return $this->belongsTo('Option', 'parent_id');
    }

    public function data(){

        return $this->hasOne('Option\Data', 'option_id', 'id');
    }

    public function companyPrices(){
        return $this->hasMany('\Company\Price');
    }

    public static function createOptionFromInputs($inputs){
        //find Option or create one
        if(isset($inputs['id'])){
            $option = Option::find($inputs['id']);

            if(!$option instanceof Option) {
                $option = new Option;
            }
        } else {
            $option = new Option;
        }

        //set Option values
        $option->name = isset($inputs['name']) ? $inputs['name'] : NULL;
        $option->parent_id = isset($inputs['parent_id']) ? $inputs['parent_id'] : NULL;
        $option->pricing_value = isset($inputs['pricing_value']) ? $inputs['pricing_value'] : NULL;
        $option->pricing_type = isset($inputs['pricing_type']) ? $inputs['pricing_type'] : NULL;
        $option->tier_formula = isset($inputs['tier_formula']) ? $inputs['tier_formula'] : NULL;
        $option->min_charge = isset($inputs['min_charge']) ? $inputs['min_charge'] : NULL;
        $option->assembler_note = isset($inputs['assembler_note']) ? $inputs['assembler_note'] : NULL;
        $option->embellisher_note = isset($inputs['embellisher_note']) ? $inputs['embellisher_note'] : NULL;
        $option->seamstress_note = isset($inputs['seamstress_note']) ? $inputs['seamstress_note'] : NULL;
        $option->notes = isset($inputs['notes']) ? $inputs['notes'] : NULL;

        $option->price_as_fabric = isset($inputs['price_as_fabric']) ? $inputs['price_as_fabric'] : 0;
        $option->is_embellishment_option = isset($inputs['is_embellishment_option']) ? $inputs['is_embellishment_option'] : 0;
        $option->is_interlining_option = isset($inputs['is_interlining_option']) ? $inputs['is_interlining_option'] : 0;

        return $option;
    }
}
