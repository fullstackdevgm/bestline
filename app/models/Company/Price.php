<?php

namespace Company;

use PricingType;

class Price extends \Eloquent
{
    protected $table = "company_prices";

    public function company(){
        return $this->belongsTo('Company');
    }

    public static function createCompanyPriceFromInputs($inputs){

    	//find Price or create one
    	if(isset($inputs['id'])){
    	    $companyPrice = Price::find($inputs['id']);

    	    if(!$companyPrice instanceof Price) {
    	        $companyPrice = new Price;
    	    }
    	} else {
    	    $companyPrice = new Price;
    	}

    	//set Price values
        $companyPrice->company_id = isset($inputs['company_id']) ? $inputs['company_id'] : NULL;
        $companyPrice->product_id = isset($inputs['product_id']) ? $inputs['product_id'] : NULL;
        $companyPrice->fabric_id = isset($inputs['fabric_id']) ? $inputs['fabric_id'] : NULL;
        $companyPrice->option_id = isset($inputs['option_id']) ? $inputs['option_id'] : NULL;
        $companyPrice->price = (isset($inputs['price']) && $inputs['price'] !== "")? $inputs['price'] : NULL;
        $companyPrice->pricing_type = (isset($inputs['pricing_type']) && $inputs['pricing_type'] !== "")?  $inputs['pricing_type'] : NULL;
        $companyPrice->tier_formula = (isset($inputs['tier_formula']) && $inputs['tier_formula'] !== "")?  $inputs['tier_formula'] : NULL;
        $companyPrice->min_charge = (isset($inputs['min_charge']) && $inputs['min_charge'] !== "")? $inputs['min_charge'] : NULL;

    	return $companyPrice;
    }

    public function getPricingType()
    {
        return PricingType::where('type', '=', $this->pricing_type)->first();
    }
}