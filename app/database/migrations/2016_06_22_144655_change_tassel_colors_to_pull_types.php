<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTasselColorsToPullTypes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::rename('tassel_colors', 'pull_types');

		Schema::table('pull_types', function(Blueprint $table)
		{
			$table->timestamps();
		});
		
		Schema::table('order_lines', function(Blueprint $table)
		{
			
			$table->dropForeign('order_lines_tassel_id_foreign');
			$table->dropColumn('tassel_id');

			$table->integer('pull_type_id')->unsigned()->nullable();

			$table->foreign('pull_type_id')
			      ->references('id')
			      ->on('pull_types')
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
		Schema::rename('pull_types', 'tassel_colors');

		Schema::table('tassel_colors', function(Blueprint $table)
		{
			$table->dropColumn('created_at');
			$table->dropColumn('updated_at');
		});

		Schema::table('order_lines', function(Blueprint $table)
		{
			$table->dropForeign('order_lines_pull_type_id_foreign');
			$table->dropColumn('pull_type_id');
			
			$table->integer('tassel_id')->unsigned()->nullable();
			$table->foreign('tassel_id')->references('id')->on('tassel_colors');
		});
	}

}
