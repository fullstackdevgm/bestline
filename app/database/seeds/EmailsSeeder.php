<?php 

class EmailsSeeder extends DatabaseSeeder {

    public function run()
    {
	    Eloquent::unguard();
	    
		DB::unprepared("SET foreign_key_checks=0");
		DB::table('emails')->truncate();
		DB::unprepared("SET foreign_key_checks=1"); 

		$emails = $this->getEmails();

        foreach($emails as $email) {
			$emailObj = Email::create($email);
			$contactObj = Contact::find($emailObj->contact_id);
			$emailObj->primaryForContact()->save($contactObj);
			$emailObj->save();
		}
    }

    protected function getEmails(){

        $emails = [];

        //add Acme contact emails
        $acme = Company::where('name', '=', 'Acme, Inc.')->firstOrFail();
        $emails[] = array(
            'email' => 'seed1@test.com',
            'contact_id' => $acme->contacts()->first()->id,
        );

        //add Happy Inc. contact emails
        $happy = Company::where('name', '=', 'Happy, Inc.')->firstOrFail();
        $emails[] = array(
            'email' => 'seed2@test.com',
            'contact_id' => $happy->contacts()->first()->id,
        );
        $emails[] = array(
            'email' => 'seed3@test.com',
            'contact_id' => $happy->contacts()->first()->id,
        );
        $emails[] = array(
            'email' => 'seed4@test.com',
            'contact_id' => $happy->contacts()->first()->id,
        );
        $emails[] = array(
            'email' => 'seed5@test.com',
            'contact_id' => $happy->contacts()->first()->id,
        );
        $emails[] = array(
            'email' => 'seed6@test.com',
            'contact_id' => $happy->contacts()->first()->id,
        );

        //add pacific view emails
        $pacificView = Company::where('name', '=', 'Pacific View Window Coverings')->firstOrFail();
        $emails[] = array(
            'email' => 'pacificview@gmail.com',
            'contact_id' => $pacificView->contacts()->first()->id,
        );

        return $emails;
    }
}