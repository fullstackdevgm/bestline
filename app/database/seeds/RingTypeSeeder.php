<?php

class RingTypeSeeder extends DatabaseSeeder
{
    public function run()
    {
        $types = [
            [
                'name' => 'none',
                'description' => 'None'
            ],
            [
                'name' => 'white',
                'description' => "White Ring"
            ],
            [
                'name' => 'brass',
                'description' => 'Brass Ring'
            ],
            [
                'name' => 'silver',
                'description' => 'Silver Grommet'
            ]
        ];

        DB::unprepared("SET foreign_key_checks=0");
        \DB::table('ring_types')->truncate();
        DB::unprepared("SET foreign_key_checks=1");

        foreach($types as $type) {
            $ringType = new RingType();
            $ringType->name = $type['name'];
            $ringType->description = $type['description'];
            $ringType->save();
        }
    }
}
