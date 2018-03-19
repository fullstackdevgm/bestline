<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RenameShippingTypeColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('shipping_types', function(Blueprint $table) {
		    $table->dropColumn('shipping_price_equation');
		});
		
		Schema::table('shipping_types', function(Blueprint $table) {
		    $table->string('formula')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('shipping_types', function(Blueprint $table) {
		    $table->dropColumn('formula');
		});
		
		Schema::table('shipping_types', function(Blueprint $table) {
		    $table->string('shipping_price_equation');
		});
	}

}