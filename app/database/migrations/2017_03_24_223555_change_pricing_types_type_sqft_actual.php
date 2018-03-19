<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePricingTypesTypeSqftActual extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('pricing_types', function(Blueprint $table)
		{
			// add new enum value 'sqft_actual'
			DB::statement("ALTER TABLE pricing_types CHANGE COLUMN type type ENUM('sqft','linear','flat','l2w1','w1','l2','l1','percent','yard','l2w2','tier','sqft_actual') NULL");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('pricing_types', function(Blueprint $table)
		{
			// remove the 'sqft_actual' type enum value
			DB::statement("ALTER TABLE pricing_types CHANGE COLUMN type type ENUM('sqft','linear','flat','l2w1','w1','l2','l1','percent','yard','l2w2','tier')");
		});
	}

}
