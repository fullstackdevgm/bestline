<?php

class ContactSeeder extends DatabaseSeeder
{
	public function run()
	{
	    Eloquent::unguard();
		
		DB::unprepared("SET foreign_key_checks=0");
		DB::table('contacts')->truncate();
		DB::table('company_contacts')->truncate();
		DB::unprepared("SET foreign_key_checks=1");
		
		$contacts = $this->getContacts();

		foreach($contacts as $contact){
			
			$company = Company::findOrFail($contact['company_id']);
			$hasCompany = $company instanceof Company;
			unset($contact['company_id']);

			$contactObj = Contact::create($contact);
			if($hasCompany){
				$contactObj->company()->save($company);
			}
			$contactObj->save();
		}

		$this->call('PhoneNumberSeeder');
		$this->call('EmailsSeeder');
	}

	public function getContacts(){
		$contacts = [];

		//add acme contacts
		$acmeId = Company::where('name', '=', 'Acme, Inc.')->firstOrFail()->id;
		$contacts[] = array(
			'first_name' => 'Joe',
			'last_name' => 'User',
			'title' => 'Owner',
			'company_id' => $acmeId
		);
		$contacts[] = array(
			'first_name' => 'Sue',
			'last_name' => 'Smith',
			'title' => 'Designer',
			'company_id' => $acmeId
		);

		//add Happy,Inc contacts
		$contacts[] = array(
			'first_name' => 'Sarah',
			'last_name' => 'Right',
			'title' => 'Designer',
			'company_id' => Company::where('name', '=', 'Happy, Inc.')->firstOrFail()->id
		);

		//add Lam's Custom Draperies
		$contacts[] = array(
			'first_name' => 'My-Ai',
			'last_name' => 'Lam-Trask',
			'title' => 'Designer',
			'company_id' => Company::where('name', '=', 'Lam\'s Custom Draperies')->firstOrFail()->id
		);

		//add The Roman Shade Company contacts
		$contacts[] = array(
			'first_name' => 'Ed',
			'company_id' => Company::where('name', '=', 'The Roman Shade Company')->firstOrFail()->id
		);

		//add The Roman Shade Company contacts
		$contacts[] = array(
			'first_name' => 'Mitch',
			'last_name' => 'Nelson',
			'title' => 'Owner',
			'company_id' => Company::where('name', '=', 'Pacific View Window Coverings')->firstOrFail()->id
		);

		//add Peggy Klock Sewing contacts
		$contacts[] = array(
			'first_name' => 'Peggy',
			'last_name' => 'Klock',
			'company_id' => Company::where('name', '=', 'Peggy Klock Sewing')->firstOrFail()->id
		);

		//add Calico Corners - Denver contacts
		$contacts[] = array(
			'first_name' => 'Benjamin',
			'last_name' => 'Franklin',
			'company_id' => Company::where('name', '=', 'Calico Corners - Denver')->firstOrFail()->id
		);

		return $contacts;
	}
}
