<?php

class RolesSeeder extends DatabaseSeeder
{
    public function run()
    {
        DB::unprepared("SET foreign_key_checks=0");
        DB::table('roles')->truncate();
        DB::table('assigned_roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('permission_role')->truncate();
        DB::unprepared("SET foreign_key_checks=1");

        $roles = $this->getRoles();
        $permissions = $this->getPermissions();

        for($i = 0; $i < count($permissions); $i++){

            $permission = $permissions[$i];
            unset($permission['roles']);
            $permission = Permission::create($permission);
            $permissions[$i]['saved'] = $permission;
        }

        foreach($roles as $roleArray){

            $role = Role::create($roleArray);
            $role->save();

            foreach($permissions as $permission){

                if($role->name === "Admin"){ //admin gets all permissions

                    $role->attachPermission($permission['saved']);

                } else if(isset($permission['roles'])){
                    foreach($permission['roles'] as $permissionRole){
                        if($role->name === $permissionRole){
                            $role->attachPermission($permission['saved']);
                        }
                    }
                }
            }
        }
    }

    public function getRoles(){

        $roles = [];

        $roles[] = array(
            'name' => 'Admin'
        );
        $roles[] = array(
            'name' => 'Floor Worker'
        );

        return $roles;
    }

    public function getPermissions(){

        $permissions = [];

        //order permissions
        $permissions[] = [
            'name' => 'update_orders',
            'display_name' => 'Update Orders',
        ];
        $permissions[] = [
            'name' => 'read_orders',
            'display_name' => 'Read Orders',
            'roles' => ['Floor Worker'],
        ];

        //company permissions
        $permissions[] = [
            'name' => 'update_companies',
            'display_name' => 'Update Companies',
        ];

        //inventory permissions
        $permissions[] = [
            'name' => 'update_inventory',
            'display_name' => 'Update Inventory',
        ];

        //administration permissions
        $permissions[] = [
            'name' => 'update_administration',
            'display_name' => 'Update Administration',
        ];

        return $permissions;
    }
}