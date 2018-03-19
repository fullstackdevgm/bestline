<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRingCalcColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('products', function(Blueprint $table) {
		    $table->decimal('ring_divisor', 8, 3);
		    $table->integer('ring_minimum')->unsigned();
		});
		
		Schema::table('finalized_order_lines', function(Blueprint $table) {
		    $table->decimal('product_ring_divisor', 8, 3);
		    $table->integer('product_ring_minimum')->unsigned();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('products', function(Blueprint $table) {
		    $table->dropColumn('ring_divisor');
		    $table->dropColumn('ring_minimum');
		});
		
		Schema::table('finalized_order_lines', function(Blueprint $table) {
		    $table->dropColumn('product_ring_divisor');
		    $table->dropColumn('product_ring_minimum');
		});
	}

}
