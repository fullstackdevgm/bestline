<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCurrentStationId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('orders', function(Blueprint $table)
		{
			$table->integer('current_station_id')->unsigned()->nullable();
			$table->foreign('current_station_id')
				->references('id')
				->on('stations')
				->onDelete('set null');
		});
		Schema::table('order_lines', function(Blueprint $table)
		{
			$table->integer('current_station_id')->unsigned()->nullable();
			$table->foreign('current_station_id')
				->references('id')
				->on('stations')
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
		Schema::table('orders', function(Blueprint $table)
		{
			$table->dropForeign('orders_current_station_id_foreign');
			$table->dropColumn('current_station_id');
		});
		Schema::table('order_lines', function(Blueprint $table)
		{
			$table->dropForeign('order_lines_current_station_id_foreign');
			$table->dropColumn('current_station_id');
		});
	}

}
