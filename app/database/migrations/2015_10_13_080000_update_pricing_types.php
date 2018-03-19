<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class UpdatePricingTypes extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    $now = Carbon::now();

    // add new enum value 'tier'
    DB::statement("ALTER TABLE pricing_types CHANGE COLUMN type type ENUM('sqft','linear','flat','l2w1','w1','l2','l1','percent','yard','l2w2','tier')");

    // insert a new record for the 'tier' type
    if (DB::table('pricing_types')->where('type', '=', 'tier')->count()==0) {
      DB::table('pricing_types')->insert(array(
        'created_at' => $now,
        'updated_at' => $now,
        'formula' => null,
        'type' => 'tier',
      ));
    }
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    // remove the 'tier' type enum value
    DB::statement("ALTER TABLE pricing_types CHANGE COLUMN type type ENUM('sqft','linear','flat','l2w1','w1','l2','l1','percent','yard','l2w2', 'tier')");
    DB::table('pricing_types')->where('type', '=', 'tier')->delete();
  }
}
