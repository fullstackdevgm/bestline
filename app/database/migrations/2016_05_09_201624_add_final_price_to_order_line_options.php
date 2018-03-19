<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFinalPriceToOrderLineOptions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_line_options', function(Blueprint $table)
		{
			$table->decimal('final_price', 8, 2)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_line_options', function(Blueprint $table)
		{
			$table->dropColumn('final_price');
		});
	}

}
