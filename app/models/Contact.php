<?php

class Contact extends Eloquent
{
  protected $table = "contacts";

  const TYPE_BILLING = 'billing';
  const TYPE_SHIPPING = 'shipping';
  const TYPE_GENERIC = 'generic';

  //setup relationships

  public function phoneNumbers()
  {
    return $this->hasMany('PhoneNumber');
  }

  public function emails(){
    return $this->hasMany('Email');
  }

  public function primaryEmail(){
    return $this->hasOne('Email', "id", "primary_email_id");
  }

  public function primaryPhoneNumber(){
    return $this->hasOne('PhoneNumber', "id",  "primary_phone_number_id");
  }
  
  public function company()
  {
    return $this->belongsToMany('Company', 'company_contacts', 'contact_id', 'company_id');
  }

  static public function getContactTypes($all = false)
  {
    //this function is obsolete but can't be removed because it's used in a migration. 
    return array();
  }

  //setup magic attributes

  public function getFullNameAttribute()
  {
    return $this->first_name . ' ' . $this->last_name;
  }

  //other functions

  static public function getValidationRules()
  {
      return array(
          'first_name' => 'required',
          'last_name' => 'required'
      );
  }

  public function getPhoneNumberAttribute(){

    $primaryPhoneNumber = $this->primaryPhoneNumber;
    $hasPrimaryPhoneNumber = $primaryPhoneNumber instanceof PhoneNumber;

    if($hasPrimaryPhoneNumber){

      return $primaryPhoneNumber;
    } else {

      $firstPhoneNumber = $this->phoneNumbers()->first();
      $hasFirstPhoneNumber = $firstPhoneNumber instanceof PhoneNumber;

      if($hasFirstPhoneNumber){

        return $firstPhoneNumber;
      } else {

        $phoneNumber = new PhoneNumber;
        $phoneNumber->number = 'No phone added.';
        return $phoneNumber;
      }
    }
  }

  public function getEmailAttribute(){
    $primaryEmail = $this->primaryEmail;
    $hasPrimaryEmail = $primaryEmail instanceof Email;

    if($hasPrimaryEmail){

      return $primaryEmail;
    } else {

      $firstEmail = $this->emails()->first();
      $hasFirstEmail = $firstEmail instanceof Email;

      if($hasFirstEmail){

        return $firstEmail;
      } else {

        $email = new Email;
        $email->email = 'No email added.';
        return $email;
      }
    }
  }
}
