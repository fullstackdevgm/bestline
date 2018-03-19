<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCutLengthFormulaData extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('products', function(Blueprint $table)
		{
			$table->dropColumn('name');
		});

		Schema::table('products', function(Blueprint $table)
		{
			$table->dropColumn('formula');
			$table->string('name')->nullable()->after('id');
			$table->decimal('width_plus', 8, 3)->nullable();
			$table->decimal('length_plus', 8, 3)->nullable();
			$table->decimal('width_times', 8, 3)->nullable();
			$table->decimal('length_times', 8, 3)->nullable();
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
			$table->string('formula')->nullable();
			$table->dropColumn('width_plus');
			$table->dropColumn('length_plus');
			$table->dropColumn('width_times');
			$table->dropColumn('length_times');
		});
	}

}
