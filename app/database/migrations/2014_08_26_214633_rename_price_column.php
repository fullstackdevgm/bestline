<?php

use Illuminate\Database\Migrations\Migration;

class RenamePriceColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE company_product_mods CHANGE price base_price decimal(6,2)');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    DB::statement('ALTER TABLE company_product_mods CHANGE base_price price decimal(6,2)');
	}

}