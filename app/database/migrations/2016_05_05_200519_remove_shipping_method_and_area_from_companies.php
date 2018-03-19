<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveShippingMethodAndAreaFromCompanies extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('companies', function(Blueprint $table)
		{
			$table->dropColumn('area');
			$table->dropForeign('companies_shipping_type_id_foreign');
			$table->dropColumn('shipping_type_id');
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
			$table->string('area')->nullable();
			$table->integer('shipping_type_id')->unsigned()->nullable();
			$table->foreign('shipping_type_id')->references('id')->on('shipping_types')->onDelete('set null');
		});
	}

}
