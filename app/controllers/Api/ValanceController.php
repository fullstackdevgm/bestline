<?php

namespace Api;

use Bestline\Math\Round;
class ValanceController extends BaseController
{
    public function calculateValance() 
    {
        return \Response::json([
            'width' => Round::toEighth(\Input::get('width', 0)),
            'height' => Round::toEighth(\Input::get('height', 0) * .1),
            'depth' => Round::toEighth(\Input::get('return', 0))
        ]);
    }
}