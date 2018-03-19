<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlertsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('alerts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('slug')->nullable();
			$table->integer('order_id')->unsigned()->nullable();
			$table->foreign('order_id')
				->references('id')
				->on('orders')
				->onDelete('cascade');
			$table->integer('order_line_id')->unsigned()->nullable();
			$table->foreign('order_line_id')
				->references('id')
				->on('order_lines')
				->onDelete('cascade');
			$table->boolean('blocks_finalization')->default(FALSE);
			$table->string('description')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{	
		Schema::drop('alerts');
	}

}
