<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreDataToOptionData extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('option_data', function(Blueprint $table)
		{	
			$table->decimal('size', 8, 3)->nullable()->after('order_line_option_id');
			$table->decimal('number', 8, 3)->nullable()->after('inset_size_top');
			$table->decimal('degrees', 8, 3)->nullable()->after('number');
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
			$table->dropColumn('number');
			$table->dropColumn('degrees');
			$table->dropColumn('size');
		});
	}

}
