<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyFabricName extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('fabrics', function(Blueprint $table) {
		    $table->dropColumn('name');
		    $table->string('pattern');
		    $table->string('color');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('fabrics', function(Blueprint $table) {
		    $table->dropColumn('pattern');
		    $table->dropColumn('color');
		    $table->string('name');
		});
	}

}
