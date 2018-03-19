<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateOrderDecimals extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_lines', function(Blueprint $table) {
		    $table->dropColumn('width');
		    $table->dropColumn('height');
		    $table->dropColumn('headerboard');
		    $table->dropColumn('return');
		});
		
	    Schema::table('order_lines', function(Blueprint $table) {
	        $table->decimal('width', 6, 3);
	        $table->decimal('height', 6, 3);
	        $table->decimal('headerboard', 6, 3);
	        $table->decimal('return', 6, 3);
	    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_lines', function(Blueprint $table) {
		    $table->dropColumn('width');
		    $table->dropColumn('height');
		    $table->dropColumn('headerboard');
		    $table->dropColumn('return');
		});
		
	    Schema::table('order_lines', function(Blueprint $table) {
	        $table->decimal('width', 3, 3);
	        $table->decimal('height', 3, 3);
	        $table->decimal('headerboard', 3, 3);
	        $table->decimal('return', 3, 3);
	    });
	}

}