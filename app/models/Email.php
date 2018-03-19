<?php

class Email extends Eloquent
{
    protected $table = "emails";

    public function contact()
    {
        return $this->belongsTo('Contact', 'contact_id');
    }

    public function primaryForContact()
    {
        return $this->hasOne('Contact', 'primary_email_id');
    }

    static public function getValidationRules()
    {
        return array(
            'email' => 'required|email'
        );
    }
}
