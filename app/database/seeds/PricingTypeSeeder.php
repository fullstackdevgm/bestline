<?php

class PricingTypeSeeder extends DatabaseSeeder
{
    protected $pricingTypes = array(
        array(
            'type' => 'sqft',
            'formula' => '((length * (width + return)) / 144)'
        ),
        array(
            'type' => 'linear',
            'formula' => '(length / 12)'
        ),
        array(
            'type' => 'flat',
            'formula' => null
        ),
        array(
            'type' => 'l2w1',
            'formula' => '((length * 2) * width)'
        ),
        array(
            'type' => 'w1',
            'formula' => '(width)'
        ),
        array(
            'type' => 'l2',
            'formula' => '(length * 2)'
        ),
        array(
            'type' => 'l1',
            'formula' => '(length)'
        ),
        array(
            'type' => 'yard',
            'formula' => '(length / 3)'
        ),
        array(
            'type' => 'l2w2',
            'formula' => '((length * 2) * (width * 2))'
        ),
        array(
            'type' => 'tier',
            'formula' => null
        ),
        array(
            'type' => 'percent',
            'formula' => null
        ),
        array(
            'type' => 'sqft_actual',
            'formula' => null
        )
    );
    
    public function run()
    {
	DB::unprepared("SET foreign_key_checks=0");
        \DB::table('pricing_types')->truncate();
	DB::unprepared("SET foreign_key_checks=1");

        Eloquent::unguard();
        
        foreach($this->pricingTypes as $type) {
            $typeObj = PricingType::create($type);
            $typeObj->save();
        }
    }
}
