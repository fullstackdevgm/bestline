<?php

class AddressSeeder extends DatabaseSeeder
{
	public function run()
	{
	    Eloquent::unguard();

		DB::unprepared("SET foreign_key_checks=0");
		DB::table('addresses')->truncate();
		DB::unprepared("SET foreign_key_checks=1");

		$addresses = $this->getAddresses();

		foreach($addresses as $address){
			$addressObj = Address::create($address);
			$addressObj->save();
		}
	}

	public function getAddresses(){
		$addresses = [];

		$shippingMethods = new stdClass();
		$shippingMethods->Delivery =  ShippingMethod::where('name', '=', 'Delivery')->firstOrFail();
		$shippingMethods->FedEx =  ShippingMethod::where('name', '=', 'FedEx')->firstOrFail();
		$shippingMethods->Pickup =  ShippingMethod::where('name', '=', 'Pickup')->firstOrFail();

		//add Acme, Inc. addresses
		$company = Company::where('name', '=', 'Acme, Inc.')->firstOrFail();
		$addresses[] = array(
			'address1' => '123 Somewhere',
			'address2' => 'Suite 100',
			'city' => 'New York',
			'state' => 'NY',
			'zip' => '10011',
			'company_id' => $company->id,
			'area' => "San Jose",
			"shipping_method_id" => $shippingMethods->FedEx->id
		);
		$addresses[] = array(
			'address1' => '12345 Elsewhere',
			'address2' => 'Suite 205',
			'city' => 'New York',
			'state' => 'NY',
			'zip' => '10011',
			'company_id' => $company->id,
			"shipping_method_id" => $shippingMethods->FedEx->id
		);

		//add Happy, Inc. addresses
		$company = Company::where('name', '=', 'Happy, Inc.')->firstOrFail();
		$addresses[] = array(
			'address1' => '555 Homestead',
			'address2' => '',
			'city' => 'New York',
			'state' => 'NY',
			'zip' => '10011',
			'company_id' => $company->id,
			"shipping_method_id" => $shippingMethods->Delivery->id
		);

		//add Lam\'s Custom Draperies addresses
		$company = Company::where('name', '=', 'Lam\'s Custom Draperies')->firstOrFail();
		$addresses[] = array(
			'address1' => '2092 Concourse Drive #81',
			'address2' => '',
			'city' => 'San Jose',
			'state' => 'CA',
			'zip' => '95121',
			'company_id' => $company->id,
			'area' => 'South Bay',
			"shipping_method_id" => $shippingMethods->Pickup->id
		);

		//add Calico Corners - Denver address
		$company = Company::where('name', '=', 'Calico Corners - Denver')->firstOrFail();
		$addresses[] = array(
			'address1' => '252 Clayton Street',
			'city' => 'Denver',
			'state' => 'CO',
			'zip' => '80206',
			'company_id' => $company->id,
			'area' => 'Ship',
			"shipping_method_id" => $shippingMethods->FedEx->id
		);

		//add The Roman Shade Company addresses
		$company = Company::where('name', '=', 'The Roman Shade Company')->firstOrFail();
		$addresses[] = array(
			'address1' => 'Some Roman Address',
			'city' => 'San Francisco',
			'state' => 'CO',
			'zip' => '000000',
			'company_id' => $company->id,
			'area' => 'Ship',
			"shipping_method_id" => $shippingMethods->FedEx->id
		);

		//add Pacific View Window Coverings address
		$company = Company::where('name', '=', 'Pacific View Window Coverings')->firstOrFail();
		$addresses[] = array(
			'address1' => '2273 Hayes Street',
			'city' => 'San Francisco',
			'state' => 'CA',
			'zip' => '94117',
			'company_id' => $company->id,
			'area' => 'San Francisco',
			"shipping_method_id" => $shippingMethods->Delivery->id
		);

		//add Peggy Klock Sewing address
		$company = Company::where('name', '=', 'Peggy Klock Sewing')->firstOrFail();
		$addresses[] = array(
			'address1' => 'Some address1',
			'city' => 'Fairfax',
			'state' => 'CA',
			'zip' => '000000',
			'company_id' => $company->id,
			'area' => 'Ship',
			"shipping_method_id" => $shippingMethods->FedEx->id
		);

		return $addresses;
	}
}
