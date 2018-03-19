<?php

namespace Api;

use Lookups\CordPosition;
use Lookups\HTSC;
use Lookups\Lining;
use Lookups\Mount;
use Lookups\Hardware;
use Lookups\PullType;
use \Order\LineItem;
use \Response;
use \Log;

class LookupsController extends \Api\BaseController
{
    public function getAll()
    {
        return Response::json(array(
            'cord_position' => CordPosition::all()->toArray(),
            'htsc' => HTSC::all()->toArray(),
            'mounts' => Mount::all()->toArray(),
            'hardware' => Hardware::all()->toArray(),
            'pull_types' => PullType::all()->toArray(),
            'height_adjustment_options' => LineItem::$heightAdjustmentOptions,
        ));
    }
    
    public function getCordPositions()
    {
        return Response::json(CordPosition::all());
    }
    
    public function getHtsc()
    {
        return Response::json(HTSC::all());
    }
    
    public function getMounts()
    {
        return Response::json(Mount::all());
    }
    
    public function getHardwares()
    {
        return Response::json(Hardware::all());
    }
    
    public function getPullTypes()
    {
        return Response::json(PullType::all());
    }
}