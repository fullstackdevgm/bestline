<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('products', function(Blueprint $table) {
		    $table->dropColumn('price');
		    $table->dropColumn('product_name');
		});
		
	    Schema::table('products', function(Blueprint $table) {
	        $table->decimal('base_price', 6, 2)->nullable();
	        $table->string('name');
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
		    $table->dropColumn('base_price');
		    $table->dropColumn('name');
		});
		
	    Schema::table('products', function(Blueprint $table) {
	        $table->decimal('price', 6, 2)->nullable();
	        $table->string('product_name');
	    });
	}

}