<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddAddressRelationsToOrder extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('orders', function(Blueprint $table) {
			$table->integer('shipping_address_id')->unsigned();
			$table->integer('billing_address_id')->unsigned();
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
			$table->dropForeign('orders_shipping_address_id_foreign');
			$table->dropColumn('shipping_address_id');
			$table->dropForeign('orders_billing_address_id_foreign');
			$table->dropColumn('billing_address_id');
		});
	}

}