<?php

class PhoneNumberSeeder extends DatabaseSeeder
{
	public function run()
	{
	    Eloquent::unguard();
	    
		DB::unprepared("SET foreign_key_checks=0");
		DB::table('phone_numbers')->truncate();
		DB::unprepared("SET foreign_key_checks=1");

		$phoneNumbers = $this->getPhoneNumbers();
	    
		foreach($phoneNumbers as $number) {
			$phoneObj = PhoneNumber::create($number);
			$phoneObj->save();
		}
	}

	protected function getPhoneNumbers(){

		$phoneNumbers = [];

		//Add Acme Inc phone numbers
		$acme = Company::where('name', '=', 'Acme, Inc.')->firstOrFail();
		$phoneNumbers[] = array(
			'number' => '2125551234',
			'type' => PhoneNumber::TYPE_PHONE, 
			'contact_id' =>$acme->contacts()->first()->id,
		);
		$phoneNumbers[] = array(
			'number' => '8105551234',
			'type' => PhoneNumber::TYPE_FAX,
			'contact_id' =>$acme->contacts()->first()->id,
		);
		$phoneNumbers[] = array(
			'number' => '7345551234',
			'type' => PhoneNumber::TYPE_PHONE,
			'contact_id' =>$acme->contacts()->first()->id,
		);

		//add pacific view phone numbers
		$pacificView = Company::where('name', '=', 'Pacific View Window Coverings')->firstOrFail();
		$phoneNumbers[] = array(
			'number' => '(415) 751-7022',
			'type' => PhoneNumber::TYPE_PHONE,
			'contact_id' => $pacificView->contacts()->first()->id,
		);

		return $phoneNumbers;
	}
}
