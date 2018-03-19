<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrimaryBillingShipping extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('companies', function(Blueprint $table)
		{
			$table->integer('primary_billing_address_id')->unsigned()->nullable();
			$table->foreign('primary_billing_address_id')
				->references('id')
				->on('addresses')
				->onDelete('set null');

			$table->integer('primary_shipping_address_id')->unsigned()->nullable();
			$table->foreign('primary_shipping_address_id')
				->references('id')
				->on('addresses')
				->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('companies', function(Blueprint $table)
		{
			$table->dropForeign('companies_primary_billing_address_id_foreign');
			$table->dropColumn('primary_billing_address_id');

			$table->dropForeign('companies_primary_shipping_address_id_foreign');
			$table->dropColumn('primary_shipping_address_id');
		});
	}

}
