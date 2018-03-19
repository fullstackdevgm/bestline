<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPoufyCalculationsToProducts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('products', function(Blueprint $table)
		{
			$table->boolean('is_poufy')->default(FALSE);
			$table->decimal('poufy_panels_to_rod', 8, 3)->nullable();
			$table->decimal('poufy_panels_to_pouf', 8, 3)->nullable();
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
		Schema::table('products', function(Blueprint $table)
		{
			$table->dropColumn('is_poufy');
			$table->dropColumn('poufy_panels_to_rod');
			$table->dropColumn('poufy_panels_to_pouf');
			$table->dropColumn('panel_height_override');
			$table->dropColumn('panel_skirt_override');
			$table->dropColumn('panel_height_max');
			$table->dropColumn('panel_height_min');
		});
	}

}
