<?php

class Part extends \Eloquent
{
    protected $table = "parts";
    
    public function getPrettyQuantityAttribute()
    {
        $quantity = $this->quantity;
    
        if($quantity >= $this->minimum_qty) {
            return $quantity;
        }
    
        return "<font color='red'>$quantity</font>";
    }

    public function inventory()
    {
        return $this->hasOne('Inventory\Part', 'part_id');
    }
    
    public function getQuantityAttribute()
    {
        if(!$this->inventory) {
            return 0;
        }
    
        return $this->inventory->quantity;
    }
}
