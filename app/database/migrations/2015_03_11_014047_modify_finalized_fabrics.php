<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyFinalizedFabrics extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('finalized_order_fabrics', function(Blueprint $table) {
		    $table->dropColumn('fabric_name');
		    $table->string('fabric_pattern');
		    $table->string('fabric_color');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('finalized_order_fabrics', function(Blueprint $table) {
		    $table->dropColumn('fabric_pattern');
		    $table->dropColumn('fabric_color');
		    $table->string('fabric_name');
		});
	}

}
