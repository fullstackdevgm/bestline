<?php

namespace Order;

use Bestline\Order\Calculator;
use \Validator;
use \Exception;
use \Order\Fabric\Option as OrderFabricOption;

class Fabric extends \Eloquent
{
    protected $table = 'order_fabrics';
    public $optionsUnsaved = [];
    public $pricing_length;
    public $pricing_width;
    
    //setup properties
    public function order()
    {
        return $this->belongsTo('Order', 'order_id');
    }    
    public function fabric()
    {
        return $this->belongsTo('Fabric');
    }    
    public function type()
    {
        return $this->belongsTo('Fabric\Type', 'fabric_type_id');
    }
    public function options()
    {
        return $this->hasMany('Order\Fabric\Option', 'order_fabric_id');
    }

    //setup magic attributes
    public function getTotalLengthAttribute(){

        return Calculator::getOrderFabricLength($this);
    }

    //other functions
    public static function getFromInputs($inputs){

        //validate input
        $validator = Validator::make($inputs, array(
            'fabric_id' => 'required',
            'fabric_type_id' => 'required',
        ));
        if($validator->fails()){throw new Exception('An order fabric is not formatted properly. Error:' . $validator->messages()->first());}

        //get fabric object
        if(isset($inputs['id'])){
            $fabric = static::find($inputs['id']);

            if(!$fabric instanceof static) {
                $fabric = new static;
            }
        } else {
            $fabric = new static;
        }

        $fabric->fabric_id = $inputs['fabric_id'];
        $fabric->fabric_type_id = $inputs['fabric_type_id'];

        foreach($inputs['options'] as $option){
            $fabric->optionsUnsaved[] = OrderFabricOption::getFromInputs($option);
        }

        return $fabric;
    }
} 