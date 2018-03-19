<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterCustomerTypeEqu extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('customer_types', function(Blueprint $table) {
			$table->dropColumn('order_pricemod_equation');
		});
		
		Schema::table('customer_types', function(Blueprint $table) {
			$table->string('order_pricemod_equation')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('customer_types', function(Blueprint $table) {
			$table->dropColumn('order_pricemod_equation');
		});
		
		Schema::table('customer_types', function(Blueprint $table) {
			$table->string('order_pricemod_equation');
		});
	}

}