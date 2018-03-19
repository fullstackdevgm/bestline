<?php

use FormulaInterpreter\Compiler;

class PricingType extends Eloquent
{
    const TYPE_SQFT = "sqft";
    const TYPE_SQFT_ACTUAL = "sqft_actual";
    const TYPE_LINEAR = "linear";
    const TYPE_FLAT = "flat";
    const TYPE_L2W1 = "l2w1";
    const TYPE_W1 = "w1";
    const TYPE_L2 = "l2";
    const TYPE_L1 = "l1";
    const TYPE_PERCENT = "percent";
    const TYPE_YARD = "yard";
    const TYPE_L2W2 = "l2w2";
    const TIER = "tier";
    
    protected $table = "pricing_types";
    
    static public function getTypes($all = true)
    {
        if($all) {
            return [
                static::TYPE_SQFT => "Square Feet",
                static::TYPE_SQFT_ACTUAL => "Actual Square Feet",
                static::TYPE_LINEAR => "Linear",
                static::TYPE_FLAT => "Flat",
                static::TYPE_L2W1 => "Length * 2 * Width",
                static::TYPE_W1 => "Width",
                static::TYPE_L2 => "Length * 2",
                static::TYPE_L1 => "Length",
                static::TYPE_PERCENT => "Percentage",
                static::TYPE_YARD => "Yard",
                static::TYPE_L2W2 => "Length * 2 * Width * 2",
                static::TIER => "Tier"
            ];
        }
        
        return [
            static::TYPE_SQFT,
            static::TYPE_SQFT_ACTUAL,
            static::TYPE_LINEAR,
            static::TYPE_FLAT,
            static::TYPE_L2W1,
            static::TYPE_W1,
            static::TYPE_L2,
            static::TYPE_L1,
            static::TYPE_PERCENT,
            static::TYPE_YARD,
            static::TYPE_L2W2,
            static::TIER
        ];
    }
    
}