<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMinChargeToCompanyPrices extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('company_prices', function(Blueprint $table)
		{
			$table->decimal('min_charge', 8, 2)->after('price')->nullable();
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
			$table->dropColumn('min_charge');
		});
	}

}
