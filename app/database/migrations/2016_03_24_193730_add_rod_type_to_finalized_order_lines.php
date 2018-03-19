<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRodTypeToFinalizedOrderLines extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('finalized_order_lines', function(Blueprint $table)
		{
			$table->string('product_rod_type')->nullable();
			$table->boolean('product_is_poufy')->default(FALSE);
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
			$table->dropColumn('product_rod_type');
			$table->dropColumn('product_is_poufy');
		});
	}

}
