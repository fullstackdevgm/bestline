<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToCompanyPrices extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('company_prices', function(Blueprint $table)
		{
			$table->string('tier_formula')->after('price')->nullable();
			$table->string('pricing_type')->after('tier_formula')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('company_prices', function(Blueprint $table)
		{
			$table->dropColumn('tier_formula');
			$table->dropColumn('pricing_type');
		});
	}

}
