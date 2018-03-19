<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalsToOrders extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('orders', function(Blueprint $table)
		{
			$table->decimal('subtotal', 8, 2)->nullable();
			$table->decimal('discount_total', 8, 2)->nullable();
			$table->decimal('rush_total', 8, 2)->nullable();
			$table->decimal('total', 8, 2)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('orders', function(Blueprint $table)
		{
			$table->dropColumn('subtotal');
			$table->dropColumn('discount_total');
			$table->dropColumn('rush_total');
			$table->dropColumn('total');
		});
	}

}
