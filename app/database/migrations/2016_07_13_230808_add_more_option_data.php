<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreOptionData extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('option_data', function(Blueprint $table)
		{
			$table->dropColumn('size');
			$table->decimal('size_bottom', 8, 3)->nullable()->after('order_line_option_id');
			$table->decimal('size_sides', 8, 3)->nullable()->after('size_bottom');
			$table->decimal('size_top', 8, 3)->nullable()->after('size_sides');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('option_data', function(Blueprint $table)
		{
			$table->decimal('size', 8, 3)->nullable();
			$table->dropColumn('size_bottom');
			$table->dropColumn('size_sides');
			$table->dropColumn('size_top');
		});
	}

}
