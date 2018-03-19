<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValancePriceToFinalizedOrderLines extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('finalized_order_lines', function(Blueprint $table)
		{
			$table->decimal('valance_price', 8, 2)->nullable()->after('shade_price');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('finalized_order_lines', function(Blueprint $table)
		{
			$table->dropColumn('valance_price');
		});
	}

}
