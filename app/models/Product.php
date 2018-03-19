<?php

class Product extends Eloquent
{
    protected $table = "products";

    protected static $rodTypes = array(
        'flat_plastic' => array(
            'name' => 'Flat Plastic',
            'deduction' =>0.5,
        ),
        'round_plastic' => array(
            'name' => 'Round Plastic',
            'deduction' => 0.5,
        ),
        'wood' => array(
            'name' => 'Wood',
            'deduction' => 0.5,
        ),
        'metal' => array(
            'name' => 'Metal',
            'deduction' => 1,
        )
    );

    public static function getRodTypesForAdmin(){
    	$select = [];

    	foreach(static::$rodTypes as $key => $type){
    		$select[$key] = $type['name'];
    	}

    	return $select;
    }

    public static function getRodType($slug){

        if($slug){
            $type = static::$rodTypes[$slug];
        } else {
            //default is round_plastic
            $type = static::$rodTypes['round_plastic'];
        }

        return $type;
    }

    public function cutLengthFormula(){

        return $this->belongsTo('\Product\CutLengthFormula', 'product_cut_length_formula_id', 'id');
    }

    public function companyPrices(){
        return $this->hasMany('\Company\Price');
    }

    public function getPricingType()
    {
        return PricingType::where('type', '=', $this->pricing_type)->first();
    }

    public function ringType()
    {
        return $this->belongsTo('RingType', 'ring_type_id');
    }

    public static function createProductFromInputs($inputs){
        //find Product or create one
        if(isset($inputs['id'])){
            $product = Product::find($inputs['id']);

            if(!$product instanceof Product) {
                $product = new Product;
            }
        } else {
            $product = new Product;
        }

        //set Product values
        $product->base_price = isset($inputs['base_price']) ? $inputs['base_price'] : NULL;
        $product->clutch_deduction = isset($inputs['clutch_deduction']) ? $inputs['clutch_deduction'] : NULL;
        $product->cord_lock_deduction = isset($inputs['cord_lock_deduction']) ? $inputs['cord_lock_deduction'] : NULL;
        $product->length_plus = isset($inputs['length_plus']) ? $inputs['length_plus'] : NULL;
        $product->length_times = isset($inputs['length_times']) ? $inputs['length_times'] : NULL;
        $product->motorized_deduction = isset($inputs['motorized_deduction']) ? $inputs['motorized_deduction'] : NULL;
        $product->name = isset($inputs['name']) ? $inputs['name'] : NULL;
        $product->panel_height_max = isset($inputs['panel_height_max']) ? $inputs['panel_height_max'] : NULL;
        $product->panel_height_min = isset($inputs['panel_height_min']) ? $inputs['panel_height_min'] : NULL;
        $product->panel_height_override = isset($inputs['panel_height_override']) ? $inputs['panel_height_override'] : NULL;
        $product->panel_skirt_override = isset($inputs['panel_skirt_override']) ? $inputs['panel_skirt_override'] : NULL;
        $product->poufy_panels_to_pouf = isset($inputs['poufy_panels_to_pouf']) ? $inputs['poufy_panels_to_pouf'] : NULL;
        $product->poufy_panels_to_rod = isset($inputs['poufy_panels_to_rod']) ? $inputs['poufy_panels_to_rod'] : NULL;
        $product->price_plus_percentage = isset($inputs['price_plus_percentage']) ? $inputs['price_plus_percentage'] : NULL;
        $product->pricing_type = isset($inputs['pricing_type']) ? $inputs['pricing_type'] : 'flat';
        $product->product_cut_length_formula_id = isset($inputs['product_cut_length_formula_id']) ? $inputs['product_cut_length_formula_id'] : NULL;
        $product->ring_divisor = isset($inputs['ring_divisor']) ? $inputs['ring_divisor'] : 0;
        $product->ring_from_edge = isset($inputs['ring_from_edge']) ? $inputs['ring_from_edge'] : NULL;
        $product->ring_minimum = isset($inputs['ring_minimum']) ? $inputs['ring_minimum'] : 0;
        $product->ring_type_id = isset($inputs['ring_type_id']) ? $inputs['ring_type_id'] : NULL;
        $product->rod_type = isset($inputs['rod_type']) ? $inputs['rod_type'] : NULL;
        $product->shape = isset($inputs['shape']) ? $inputs['shape'] : NULL;
        $product->width_plus = isset($inputs['width_plus']) ? $inputs['width_plus'] : NULL;
        $product->width_plus_lining = isset($inputs['width_plus_lining']) ? $inputs['width_plus_lining'] : NULL;
        $product->width_times = isset($inputs['width_times']) ? $inputs['width_times'] : NULL;
        $product->cut_length_add = isset($inputs['cut_length_add']) ? $inputs['cut_length_add'] : 0;
        $product->is_frontslat = isset($inputs['is_frontslat']) ? $inputs['is_frontslat'] : 0;
        $product->is_casual = isset($inputs['is_casual']) ? $inputs['is_casual'] : 0;
        $product->is_poufy = isset($inputs['is_poufy']) ? $inputs['is_poufy'] : 0;

        return $product;
    }
}