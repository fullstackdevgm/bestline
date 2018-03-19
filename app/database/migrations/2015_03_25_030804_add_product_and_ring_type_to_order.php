<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductAndRingTypeToOrder extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    DB::unprepared("SET foreign_key_checks=0");
		Schema::table('orders', function(Blueprint $table) {
		    
		    $table->integer('product_id')->unsigned();
		    $table->integer('ring_type_id')->unsigned();
		    
		    $table->foreign('product_id')
		          ->references('id')
		          ->on('products');
		          
		    $table->foreign('ring_type_id')
		          ->references('id')
		          ->on('ring_types');
		});
	    DB::unprepared("SET foreign_key_checks=1");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    DB::unprepared("SET foreign_key_checks=0");
		Schema::table('orders', function(Blueprint $table) {
		    
		    $table->dropForeign('orders_product_id_foreign');
		    $table->dropForeign('orders_ring_type_id_foreign');
		    $table->dropColumn('product_id');
		    $table->dropColumn('ring_type_id');
		});
	    DB::unprepared("SET foreign_key_checks=1");
	}

}
