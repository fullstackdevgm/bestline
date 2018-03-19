<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropCompanyCreditTermsFromFinalizedOrders extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('finalized_orders', function(Blueprint $table)
		{
			$table->dropColumn('company_credit_terms');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('finalized_orders', function(Blueprint $table)
		{
			$table->string('company_credit_terms');
		});
	}

}
