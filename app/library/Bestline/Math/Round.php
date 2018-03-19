<?php

namespace Bestline\Math;

use Log;

class Round
{
    static public function toEighth($originalNumber)
    {
        $originalInteger = floor($originalNumber);
        $originalDecimal = $originalNumber - $originalInteger;

        if(!$originalDecimal){
            return $originalNumber;
        }

        $decimalAsThousandInteger = $originalDecimal * 1000;
        $eigthInchCount = round($decimalAsThousandInteger / 125, 0);
        $roundedDecimal = ($eigthInchCount * 125) / 1000;
        $roundedNumber = $originalInteger + $roundedDecimal;
        return $roundedNumber;
    }

    static public function toNearestYard($feet){
        return ceil($feet/3.0) * 3.0;
    }
    static public function toNearestSixInches($inches){
        return ceil($inches/6) * 6;
    }
}