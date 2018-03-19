<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPoufyCalculationsToFinalizedOrderLines extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('finalized_order_lines', function(Blueprint $table)
		{
			$table->decimal('num_extra_panels', 8, 3)->nullable();
			$table->integer('height_adjustment_option_id')->nullable();
			$table->decimal('panel_height_override', 8, 3)->nullable();
			$table->decimal('panel_skirt_override', 8, 3)->nullable();
			$table->decimal('panel_height_max', 8, 3)->nullable();
			$table->decimal('panel_height_min', 8, 3)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('finalized_order_lines', function(Blueprint $table)
		{
			$table->dropColumn('num_extra_panels');
			$table->dropColumn('height_adjustment_option_id');
			$table->dropColumn('panel_height_override');
			$table->dropColumn('panel_skirt_override');
			$table->dropColumn('panel_height_max');
			$table->dropColumn('panel_height_min');
		});
	}

}
