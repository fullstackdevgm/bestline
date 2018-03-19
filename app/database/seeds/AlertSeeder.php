<?php

class AlertSeeder extends DatabaseSeeder
{
    public function run()
    {

        DB::unprepared("SET foreign_key_checks=0");
        DB::table('alerts')->truncate();
        DB::unprepared("SET foreign_key_checks=1");

        $alerts = $this->getAlerts();

        foreach($alerts as $alert) {
            $alert = Alert::create($alert);
            $alert->save();
        }
    }

    private function getAlerts(){

        $alerts = [];

        $alerts[] = array(
            'slug' => 'orderline-needs-mount',
            'description' => 'Each orderline needs to have a mount specified.',
            'blocks_finalization' => '1',
        );

        return $alerts;

    }
}
