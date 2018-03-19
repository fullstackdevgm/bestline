<?php

namespace CompanySeeder;

use \Company;
use \Company\Price as CompanyPrice;
use \Fabric;
use \Product;
use \Option;
use \Eloquent;
use \DB;
use \DatabaseSeeder;

class PriceSeeder extends DatabaseSeeder
{
	public function run()
	{
		Eloquent::unguard();

		DB::unprepared("SET foreign_key_checks=0");
		CompanyPrice::truncate();
		DB::unprepared("SET foreign_key_checks=1");

		$companyPrices = $this->getPrices();

		foreach($companyPrices as $price){

			$priceObj = CompanyPrice::create($price);
			$priceObj->save();
		}
	}

	protected function getPrices(){

	    $prices = [];

	    //Add Acme Inc phone numbers
	    $company = Company::where('name', '=', 'Unknown')->firstOrFail();
	    $fabric = Fabric::where('pattern', '=', 'Aurora')->firstOrFail();
	    $product = Product::where('name', '=', 'Austrian')->firstOrFail();
	    $prices[] = array(
	    	'price' => 15,
	    	'company_id' =>$company->id,
	    	'fabric_id' => $fabric->id,
	    );
	    $product = Product::where('name', '=', 'Austrian')->firstOrFail();
	    $prices[] = array(
	    	'price' => 15,
	    	'company_id' =>$company->id,
	    	'product_id' => $product->id,
	    );
	    $company = Company::where('name', '=', 'Acme, Inc.')->firstOrFail();
	    $product = Option::where('name', '=', 'Back Flap')->firstOrFail();
	    $prices[] = array(
	    	'price' => 15,
	    	'company_id' =>$company->id,
	    	'option_id' => $product->id,
	    );

	    return $prices;
	}
}