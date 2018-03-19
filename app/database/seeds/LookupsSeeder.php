<?php

class LookupsSeeder extends Seeder
{
    public function run()
    {
        Eloquent::unguard();

        $this->call('LookupSeeder\CordPositionsSeeder');
        $this->call('LookupSeeder\MountsSeeder');
        $this->call('LookupSeeder\HardwareSeeder');
        $this->call('LookupSeeder\PullTypesSeeder');
        $this->call('LookupSeeder\ValanceTypeSeeder');
    }
}
