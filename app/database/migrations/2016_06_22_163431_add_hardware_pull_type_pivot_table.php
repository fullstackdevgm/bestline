<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHardwarePullTypePivotTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hardware_pull_type', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('pull_type_id')->unsigned()->nullable();
			$table->foreign('pull_type_id')
			      ->references('id')
			      ->on('pull_types')
			      ->onDelete('cascade');

			$table->integer('hardware_id')->unsigned()->nullable();
			$table->foreign('hardware_id')
			      ->references('id')
			      ->on('hardware')
			      ->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hardware_pull_type');
	}

}
