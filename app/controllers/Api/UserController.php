<?php

namespace Api;

use \Response;
use \Auth;

class UserController extends BaseController
{
    public function getCurrent() 
    {
    	$user = AUTH::user();
    	$user->load('station', 'openOrderStations', 'openOrderStations.order');

        return Response::json($user);
    }
}