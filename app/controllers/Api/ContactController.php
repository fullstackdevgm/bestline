<?php

namespace Api;

use \Contact;
use \Response;
use \Log;
use \Email;
use \PhoneNumber;
use \Input;
use \Validator;
use \Schema;
use \App;

class ContactController extends \Api\BaseController
{
    public function getNewEmail(){
    	$emailFields = Schema::getColumnListing('emails');

    	$newEmail = new Email;
    	foreach($emailFields as $field){
    		$newEmail->{$field} = null;
    	}
        $newEmail->updated_at = date('Y-m-d H:i:s',time());

    	return Response::json($newEmail);
    }

	public function putEmail($contactId, $emailId){
        $data = Input::all();

        //validate the data
        $validator = Validator::make(
        	$data,
            Email::getValidationRules()
        );
        if($validator->fails()) {
            return Response::json($validator->errors(), 422);
        }

        //if the id exists, grab the email, if not, create a new one and attach it to the contact
        if(isset($data['id'])) {
            $email = Email::find($data['id']);
        } else {

            if(isset($contactId)) {
                $contact = Contact::find($contactId);
            }

            if(!$contact instanceof Contact) {
                return Response::json("Contact not found.", 410);
            }

            $email = new Email;
            $contact->emails()->save($email);
        }

        if(!$email instanceof Email) {
        	return Response::json("Email not found.", 410);
        }

        //update and save the email
        $email->email = Input::get('email');
        $email->save();
        
        return Response::json($email->toArray());
    }

    public function deleteEmail($contactId, $emailId){ 
    	$email = Email::find($emailId);

    	if(!$email instanceof Email) {
    		App::abort(404, 'Not found. Cannot remove email with id ' . $emailId . '.');
    	}
        $email->delete();
        
        return Response::json($email->email . " Deleted");
    }

    public function getNewPhoneNumber(){
        $phoneNumberFields = Schema::getColumnListing('phone_numbers');

        $newPhoneNumber = new PhoneNumber;
        foreach($phoneNumberFields as $field){
            $newPhoneNumber->{$field} = null;
        }
        $newPhoneNumber->updated_at = date('Y-m-d H:i:s',time());

        return Response::json($newPhoneNumber);
    }

    public function putPhoneNumber($contactId, $phoneNumberId){
        $data = Input::all();

        //validate the data
        $validator = Validator::make(
            $data,
            PhoneNumber::getValidationRules()
        );
        if($validator->fails()) {
            return Response::json($validator->errors(), 422);
        }

        //if the id exists, grab the email, if not, create a new one and attach it to the contact
        if(isset($data['id'])) {
            $phoneNumber = PhoneNumber::find($data['id']);
        } else {

            if(isset($contactId)) {
                $contact = Contact::find($contactId);
            }

            if(!$contact instanceof Contact) {
                return Response::json("Contact not found.", 410);
            }

            $phoneNumber = new PhoneNumber;
            $contact->phoneNumbers()->save($phoneNumber);
        }

        if(!$phoneNumber instanceof PhoneNumber) {
            return Response::json("Phone number not found.", 410);
        }

        //update and save the phone number
        $phoneNumber->number = Input::get('number');
        $phoneNumber->type = Input::get('type');
        $phoneNumber->save();
        
        return Response::json($phoneNumber->toArray());
    }

    public function deletePhoneNumber($contactId, $phoneNumberId){ 
        $phoneNumber = PhoneNumber::find($phoneNumberId);

        if(!$phoneNumber instanceof PhoneNumber) {
            App::abort(404, 'Not found. Cannot remove phone number with id ' . $phoneNumberId . '.');
        }
        $phoneNumber->delete();
        
        return Response::json($phoneNumber->email . " Deleted");
    }
}
