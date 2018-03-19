<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropFinalizedTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::unprepared("SET foreign_key_checks=0");
		Schema::drop('finalized_orders');
		Schema::drop('finalized_order_default_options');
		Schema::drop('finalized_order_lines');
		Schema::drop('finalized_order_fabrics');
		Schema::drop('finalized_order_line_options');
		DB::unprepared("SET foreign_key_checks=1");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//Can't undo, just rebuild the database without this and the following two commits
	}

}
