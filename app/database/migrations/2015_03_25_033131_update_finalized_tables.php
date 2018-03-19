<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFinalizedTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('finalized_order_lines', function(Blueprint $table) {
		    $table->dropColumn('product_ring_type');
		});
		
	    Schema::table('finalized_order_lines', function(Blueprint $table) {
	        $table->string('product_ring_type');
	    });
		    
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('finalized_order_lines', function(Blueprint $table) {
		    $table->dropColumn('product_ring_type');
		});
		
	    Schema::table('finalized_order_lines', function(Blueprint $table) {
	        $table->enum('product_ring_type', ['brass', 'silver', 'white']);
	    });
	}

}
