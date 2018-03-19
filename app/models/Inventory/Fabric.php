<?php

namespace Inventory;

class Fabric extends \Eloquent
{
    protected $table = "fabrics_inventory";

    public function fabric()
    {
        return $this->belongsTo('Fabric');
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

        if($this->attributes['quantity'] < $this->fabric->minimum_qty) {
            return '<font color="red">' . $quantity .'</font>';
        }

        return $quantity;
    }

    public function getQuantityAttribute()
    {
        $latest = static::where('fabric_id', '=', $this->fabric_id)
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