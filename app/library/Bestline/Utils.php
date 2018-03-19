<?php

namespace Bestline;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;
class Utils
{
	static public function isTraversable($input) 
	{
		return is_array($input) || ($input instanceof \Traversable) || ($input instanceof \stdClass);
	}
	
	static public function formatPhoneNumber($number)
	{
		$phoneUtils = PhoneNumberUtil::getInstance();
		
		try {
			$usPhoneProto = $phoneUtils->parse($number, "US");
			return $phoneUtils->format($usPhoneProto, PhoneNumberFormat::NATIONAL);
		} catch(NumberParseException $e) {
			return "(000) 000-0000";
		}
	}

	static public function getArrayFromEloquent($array){
	    $newArray = [];
	    foreach($array as $item){
	        $newArray[] = $item->toArray();
	    }

	    return $newArray;
	}
}