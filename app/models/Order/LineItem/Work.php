<?php

namespace Order\LineItem;

class Work extends \Eloquent
{
    // use \SoftDeletingTrait;    
    // protected $dates = ['deleted_at'];
    protected $softDelete = true;
    protected $table = 'order_line_work';
    
    //setup properties
    public function station()
    {
        return $this->belongsTo('Station');
    } 
    public function user()
    {
        return $this->belongsTo('User');
    } 
    public function order()
    {
        return $this->belongsTo('Order');
    }
    public function orderLine()
    {
        return $this->belongsTo('Order\LineItem');
    }

    //setup magic attributes

    //other functions
} 