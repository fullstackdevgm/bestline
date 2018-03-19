<?php

namespace Order\Fabric;

use \Log;
use \Order\Option as OrderOption;

class Option extends OrderOption
{
    protected $table = "order_fabric_options";

    //setup relationships
    public function orderFabric()
    {
        return $this->belongsTo('Order\Fabric', 'order_fabric_id');
    }

    public function order(){
        return $this->belongsTo('Order', 'order_id');
    }    

    public function data(){
        return $this->hasOne('Option\Data', 'order_fabric_option_id', 'id');
    }
}