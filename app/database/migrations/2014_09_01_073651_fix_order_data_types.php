<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class FixOrderDataTypes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('orders', function(Blueprint $table) {
		    $table->dropColumn('discount_formula');
		    $table->dropColumn('rush_cost_formula');
		    $table->dropColumn('boxing_cost_formula');
		});
		
		Schema::table('orders', function(Blueprint $table) {
		    $table->string('discount_formula')->nullable();
		    $table->string('rush_cost_formula')->nullable();
		    $table->string('boxing_cost_formula')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('orders', function(Blueprint $table) {
		    $table->dropColumn('discount_formula');
		    $table->dropColumn('rush_cost_formula');
		    $table->dropColumn('boxing_cost_formula');
		});
		
		Schema::table('orders', function(Blueprint $table) {
		    $table->decimal('discount_formula');
		    $table->decimal('rush_cost_formula');
		    $table->decimal('boxing_cost_formula');
		});
	}

}