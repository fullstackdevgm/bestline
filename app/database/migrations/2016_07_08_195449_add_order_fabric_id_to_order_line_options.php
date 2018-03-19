<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderFabricIdToOrderLineOptions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_line_options', function(Blueprint $table)
		{
			$table->integer('order_fabric_id')->unsigned()->nullable();
			$table->foreign('order_fabric_id')
				->references('id')
				->on('order_fabrics')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_line_options', function(Blueprint $table)
		{
			$table->dropForeign('order_line_options_order_fabric_id_foreign');
			$table->dropColumn('order_fabric_id');
		});
	}

}
