<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShippingMethodToAddresses extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('addresses', function(Blueprint $table){

			$table->string('area')->nullable();
			$table->integer('shipping_method_id')->unsigned()->nullable();
			$table->foreign('shipping_method_id')->references('id')->on('shipping_types')->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('addresses', function(Blueprint $table)
		{	
			$table->dropColumn('area');
			$table->dropForeign('addresses_shipping_method_id_foreign');
			$table->dropColumn('shipping_method_id');
		});
	}

}
