<?php

namespace Order\LineItem;

use \Log;
use \Order\Option as OrderOption;
use \stdClass;

class Option extends OrderOption
{
    protected $table = "order_line_options";
    public $pricing_length;
    public $pricing_width;

    public function orderLine()
    {
        return $this->belongsTo('Order\LineItem', 'order_line_id');
    }

    public function orderFabric()
    {
        return $this->hasOne('Order\Fabric', 'id', 'order_fabric_id');
    }

    public function data(){
        return $this->hasOne('Option\Data', 'order_line_option_id', 'id');
    }

    //other functions (see OrderOption for additional)
    public function finalize(){

        $this->final_price = $this->price;
        $this->save();

        return $this;
    }

    public function getPrettyPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    public function getCuttingsAttribute(){

        $option = $this;

        $hasData = ($option->data)? true : false;
        if(!$hasData){
            return new stdClass();
        }

        $orderLine = $option->order_line;
        $product = $orderLine->product;
        $cuttings = [];
        $optionDataKeys = ['size_top', 'size_sides', 'size_bottom'];
        $headerboard = ($orderLine->headerboard > 0)? $orderLine->headerboard: $orderLine->valance_headerboard;

        foreach($optionDataKeys as $key){
            if($option->data->{$key}){

                $cutting = new stdClass();
                $cutting->finished = $option->data->{$key};

                if($key === 'size_top' || $key === 'size_bottom'){
                    $cutting->count = 1;
                    $cutting->length = ceil($orderLine->manufacturing_width) + 6;
                } else if($key === 'size_sides') {
                    $cutting->count = 2;
                    if($product->is_casual || $product->is_frontslat){
                        $cutting->length = ceil($orderLine->manufacturing_length) + ceil($headerboard) + 6;
                    } else {
                        $cutting->length = ceil($orderLine->manufacturing_length);
                    }
                }

                $cutting->width = ($cutting->finished * 2) + .5;

                $cuttings[] = $cutting;
            }
        }

        return $cuttings;
    }

    public function getShapeAttribute(){

        $shape = null;

        //set embellishment shape
        if($this->option){
            if($this->option->name === 'Tassel Trim'){
               $shape = 'tassel';
            } else if($this->option->name === 'Banding' || $this->option->name === 'Trim'){
               $shape = 'filled';
            }
        }

        return $shape;
    }
}