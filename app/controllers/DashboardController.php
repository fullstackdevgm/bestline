<?php

use \Log;

class DashboardController extends Controller
{
	public function indexAction()
	{
	    return View::make('dashboard.index');
	}
}
