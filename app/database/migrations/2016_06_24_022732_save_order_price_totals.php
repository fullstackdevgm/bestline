<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SaveOrderPriceTotals extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_lines', function(Blueprint $table)
		{
			$table->decimal('shade_price', 8, 2)->nullable();
			$table->decimal('fabric_price', 8, 2)->nullable();
			$table->decimal('options_price', 8, 2)->nullable();
			$table->decimal('total_price', 8, 2)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_lines', function(Blueprint $table)
		{
			$table->dropColumn('shade_price');
			$table->dropColumn('fabric_price');
			$table->dropColumn('options_price');
			$table->dropColumn('total_price');
		});
	}

}
