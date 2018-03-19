<?php

namespace Inventory;

class Part extends \Eloquent
{
    protected $table = "parts_inventory";
    
    public function part()
    {
        return $this->belongsTo('Part');
    }
    
    public function byUser()
    {
        return $this->belongsTo('User', 'by_user_id');
    }
    
    public function getPrettyAdjustmentAttribute()
    {
        $adjustment = number_format($this->adjustment, 0);
    
        if($this->adjustment < 0) {
            return '<font color="red">(' . abs($adjustment) .')</font>';
        }
    
        return $adjustment;
    }
    
    public function getPrettyQuantityAttribute()
    {
        $quantity = number_format($this->attributes['quantity'], 0);
    
        if($this->attributes['quantity'] < $this->part->minimum_qty) {
            return '<font color="red">' . $quantity .'</font>';
        }
    
        return $quantity;
    }
    
    public function getQuantityAttribute()
    {
        $latest = static::where('part_id', '=', $this->part_id)
                        ->orderBy('created_at', 'desc')
                        ->first();
    
        return $latest->attributes['quantity'];
    }
    
    public function getUserFullNameAttribute()
    {
        if($this->byUser) {
            return $this->byUser->full_name;
        }
    
        return "Automated";
    }
}