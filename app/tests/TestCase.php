<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase 
{
    static protected $_app = null;
    
	/**
	 * Creates the application.
	 *
	 * @return Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';
        
		if(is_null(static::$_app)) {
		  static::$_app = require __DIR__.'/../../bootstrap/start.php';
		}
		
		return static::$_app; 
	}
}
