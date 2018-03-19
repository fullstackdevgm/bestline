<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class UpdatePermissions extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    $now = Carbon::now();

    if (DB::table('permissions')->where('name', 'like', 'admin_options_%')->count() == 0) {
      // Add new options permissions
      DB::table('permissions')->insert(array(
        array(
          'name' => 'admin_options_read',
          'display_name' => 'Can read options',
          'created_at' => $now,
          'updated_at' => $now,
        ),
        array(
          'name' => 'admin_options_write',
          'display_name' => 'Can create/edit options',
          'created_at' => $now,
          'updated_at' => $now,
        ),
        array(
          'name' => 'admin_options_delete',
          'display_name' => 'Can delete options',
          'created_at' => $now,
          'updated_at' => $now,
        ),
      ));
    }

    // get permissions and admin role
    $permissions = DB::table('permissions')->where('name', 'like', 'admin_options_%')->get();
    $admin_role = DB::table('roles')->where('name', '=', 'Admin')->first();

    if(is_object($admin_role) && is_object($permissions)){

      $insert_data = array();

      foreach ($permissions as $permission) {
        $insert_data[] = array(
          'permission_id' => $permission->id,
          'role_id' => $admin_role->id,
        );
      }

      // Give these permissions to the admin user
      DB::table('permission_role')->insert($insert_data);
    }
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    // Undo this by hand
  }

}
