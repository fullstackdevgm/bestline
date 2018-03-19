<?php

use Bestline\Utils;

abstract class AbstractSimpleModel
{
	static public $_validationMessages = null;
	static protected $rules = array();
	
	static public function validate($input = null) 
	{
		if(is_null($input)) {
			$input = Input::all();
		}
		
		$validator = Validator::make($input, static::$rules);
		
		if($validator->passes()) {
			return true;
		} else {
			Input::flash();
			static::$_validationMessages = $validator->getMessageBag()->getMessages();
			return false;
		}
	}

	public function __construct($input = null)
	{
		if(Utils::isTraversable($input)) {
			foreach($input as $key => $val) {
				$this->$key = $val;
			}
		}
	}
}