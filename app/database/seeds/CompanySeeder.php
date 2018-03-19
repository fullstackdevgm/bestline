<?php

class CompanySeeder extends DatabaseSeeder
{
	public function run()
	{
	    Eloquent::unguard();

		$this->call('CustomerTypeSeeder');

		DB::unprepared("SET foreign_key_checks=0");
		DB::table('companies')->truncate();
		DB::unprepared("SET foreign_key_checks=1");

		$companies = $this->getCompanies();

		foreach($companies as $company) {

			$companyObj = Company::create($company);
			$companyObj->save();
		}

		$this->call('AddressSeeder');
	}

	private function getCompanies(){

		$companies = [];

		$customerTypes = new stdclass();
		$customerTypes->normalCustomer = CustomerType::where('name', '=', 'Normal Customer')->firstOrFail();

		$creditTerms = new stdclass();
		$creditTerms->cod = 'cod';
		$creditTerms->net10 = 'net10';

		$companies[] = array(
			'name' => "Unknown",
			'credit_terms' => $creditTerms->net10,
		);
		$companies[] = array(
			'website' => 'http://www.example.com',
			'notes' => "Test notes for company",
			'account_no' => '123456AB',
			'name' => "Acme, Inc.",
			'credit_term_notes' => "Test notes for credit terms",
			'credit_terms' => $creditTerms->net10,
		);
		$companies[] = array(
			'website' => 'http://www.example.com',
			'notes' => "Test notes for company",
			'account_no' => '123456AB',
			'name' => "Happy, Inc.",
			'credit_term_notes' => "Test notes for credit terms",
			'credit_terms' => $creditTerms->net10,
		);
		$companies[] = array(
			'name' => "Lam's Custom Draperies",
			'customer_type_id' => $customerTypes->normalCustomer->id,
			'credit_terms' => $creditTerms->net10,
		);
		$companies[] = array(
			'account_no' => 111,
			'name' => "Calico Corners - Denver",
			'customer_type_id' => $customerTypes->normalCustomer->id,
			'credit_terms' => $creditTerms->net10,
		);
		$companies[] = array(
			'account_no' => 111,
			'name' => "The Roman Shade Company",
			'customer_type_id' => $customerTypes->normalCustomer->id,
			'credit_terms' => $creditTerms->net10,
		);
		$companies[] = array(
			'name' => "Pacific View Window Coverings",
			'customer_type_id' => $customerTypes->normalCustomer->id,
			'credit_terms' => $creditTerms->net10,
		);
		$companies[] = array(
			'name' => "Peggy Klock Sewing",
			'customer_type_id' => $customerTypes->normalCustomer->id,
			'credit_terms' => $creditTerms->net10,
		);

		return $companies;
	}
}
