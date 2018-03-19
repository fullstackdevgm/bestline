<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeProductsPricingTypeSqftActual extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('products', function(Blueprint $table)
		{
			// add new enum value 'sqft_actual'
			DB::statement("ALTER TABLE products CHANGE COLUMN pricing_type pricing_type ENUM('sqft','linear','flat','sqft_actual') NULL");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('products', function(Blueprint $table)
		{
			// remove the 'sqft_actual' type enum value
			DB::statement("ALTER TABLE products CHANGE COLUMN pricing_type pricing_type ENUM('sqft','linear','flat')");
		});
	}

}
