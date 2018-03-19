<?php

class PhoneNumber extends Eloquent
{
    const TYPE_PHONE = 'voice';
    const TYPE_FAX = 'fax';

    protected $table = "phone_numbers";

    static public function getTypes($all = false)
    {
        if($all) {
            return array(
                static::TYPE_PHONE => "Voice",
                static::TYPE_FAX => "Fax"
            );
        }

        return array(
            static::TYPE_PHONE,
            static::TYPE_FAX
        );
    }

    public function contacts()
    {
        return $this->belongsTo('Contact', 'contact_id');
    }

    public function primaryForContact()
    {
        return $this->hasOne('Contact', 'primary_phone_number_id');
    }

    public function getFormattedPhoneNumberAttribute()
    {
        $phoneNumber = preg_replace('/[^0-9]/', '', $this->number);

        if(strlen($phoneNumber) > 10) {
            $countryCode = substr($phoneNumber, 0, strlen($phoneNumber)-10);
            $areaCode = substr($phoneNumber, -10, 3);
            $nextThree = substr($phoneNumber, -7, 3);
            $lastFour = substr($phoneNumber, -4, 4);

            $phoneNumber = '+'.$countryCode.' ('.$areaCode.') '.$nextThree.'-'.$lastFour;
        }
        else if(strlen($phoneNumber) == 10) {
            $areaCode = substr($phoneNumber, 0, 3);
            $nextThree = substr($phoneNumber, 3, 3);
            $lastFour = substr($phoneNumber, 6, 4);

            $phoneNumber = '('.$areaCode.') '.$nextThree.'-'.$lastFour;
        }
        else if(strlen($phoneNumber) == 7) {
            $nextThree = substr($phoneNumber, 0, 3);
            $lastFour = substr($phoneNumber, 3, 4);

            $phoneNumber = $nextThree.'-'.$lastFour;
        }

        return $phoneNumber;
    }

    static public function getValidationRules()
    {
        return array(
            'number' => 'required'
        );
    }
}
