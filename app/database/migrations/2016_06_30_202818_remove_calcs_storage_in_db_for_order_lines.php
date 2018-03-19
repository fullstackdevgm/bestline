<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCalcsStorageInDbForOrderLines extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_lines', function(Blueprint $table)
		{
			$table->dropColumn('tdbu_deduction');
			$table->dropColumn('num_extra_panels');
			$table->dropColumn('panel_height_override');
			$table->dropColumn('panel_skirt_override');
			$table->dropColumn('panel_height_max');
			$table->dropColumn('panel_height_min');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_lines', function(Blueprint $table)
		{
			$table->decimal('tdbu_deduction', 8, 3)->nullable();
			$table->decimal('num_extra_panels', 8, 3)->nullable();
			$table->decimal('panel_height_override', 8, 3)->nullable();
			$table->decimal('panel_skirt_override', 8, 3)->nullable();
			$table->decimal('panel_height_max', 8, 3)->nullable();
			$table->decimal('panel_height_min', 8, 3)->nullable();
		});
	}

}
