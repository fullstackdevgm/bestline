<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductCutLengthFormulasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_cut_length_formulas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->nullable();
			$table->timestamps();
		});

		Schema::table('products', function(Blueprint $table)
		{
			$table->integer('product_cut_length_formula_id')->unsigned()->nullable()->after('is_backslat');
			$table->foreign('product_cut_length_formula_id')
				->references('id')
				->on('product_cut_length_formulas')
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
		Schema::table('products', function(Blueprint $table)
		{
			$table->dropForeign('products_product_cut_length_formula_id_foreign');
			$table->dropColumn('product_cut_length_formula_id');
		});

		Schema::drop('product_cut_length_formulas');
	}
}
