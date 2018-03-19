<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTbduDeductionsToProducts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('products', function(Blueprint $table)
		{
			$table->boolean('is_tdbu')->default(FALSE);
			$table->decimal('clutch_deduction', 8, 3)->nullable();
			$table->decimal('cord_lock_deduction', 8, 3)->nullable();
			$table->decimal('motorized_deduction', 8, 3)->nullable();
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
			$table->dropColumn('is_tdbu');
			$table->dropColumn('clutch_deduction');
			$table->dropColumn('cord_lock_deduction');
			$table->dropColumn('motorized_deduction');
		});
	}

}
