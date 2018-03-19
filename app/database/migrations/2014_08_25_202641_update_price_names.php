<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdatePriceNames extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('fabrics', function(Blueprint $table) {
		    DB::statement('ALTER TABLE fabrics CHANGE price unit_price decimal(8,2)');
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
		    DB::statement('ALTER TABLE fabrics CHANGE unit_price price decimal(8,2)');
		});
	}

}