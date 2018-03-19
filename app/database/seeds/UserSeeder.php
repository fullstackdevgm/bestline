<?php

class UserSeeder extends DatabaseSeeder
{
    public function run()
    {
        Eloquent::unguard();
        DB::unprepared("SET foreign_key_checks=0");
        \DB::table('users')->truncate();
        DB::unprepared("SET foreign_key_checks=1");

        $this->call('RolesSeeder');

        $users = $this->getUsers();

        foreach ($users as $user)
        {
            $userObj = new User();

            foreach($user as $key => $val) {
                if($key != 'roles') {
                    $userObj->$key = $val;
                }
            }

            $userObj->save();

            foreach($user['roles'] as $roleName) {
                $role = Role::where('name', '=', $roleName)->first();

                if(!$role instanceof Role) {
                    throw new \Exception("Failed to load role '$roleName'");
                }

                $userObj->attachRole($role);
            }
        }
    }

    private function getUsers(){

        $users = [];

        //add default users
        $users[] = [
            "username" => "bestline",
            "password" => "bestline",
            'first_name' => 'Bestline',
            'last_name' => 'User',
            "email" => "bestline@bestline.com",
            'roles' => array(
                'Admin'
            ),
            'station_id' => Station::where('name', '=', 'Cutter')->firstOrFail()->id,
        ];

        $users[] = [
            "username" => "floorworker",
            "password" => "floorworker",
            'first_name' => 'Floor',
            'last_name' => 'Worker',
            "email" => "bestline@bestline.com",
            'roles' => array(
                'Floor Worker'
            ),
            'station_id' => Station::where('name', '=', 'Cutter')->firstOrFail()->id,
        ];

        return $users;
    }
}
