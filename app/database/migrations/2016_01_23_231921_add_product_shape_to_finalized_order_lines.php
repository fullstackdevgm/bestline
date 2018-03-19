<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductShapeToFinalizedOrderLines extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('finalized_order_lines', function(Blueprint $table)
		{
			$table->enum('product_shape', ['Austrian', 'Square/Trapezoid', 'Square', 'Dog Ear', 'Balloon', 'SCloud', 'PCloud', 'TDBU/BU'])->nullable();
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
			$table->dropColumn('product_shape');
		});
	}

}
