<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderFinalLinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('finalized_order_lines', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();

			//foreign keys
			$table->integer('order_line_id')->unsigned()->nullable();
			$table->foreign('order_line_id')->references('id')->on('order_lines')->onDelete('cascade');

			$table->decimal('shade_price', 8, 2)->nullable();
			$table->decimal('fabric_price', 8, 2)->nullable();
			$table->decimal('options_price', 8, 2)->nullable();
			$table->decimal('total_price', 8, 2)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('finalized_order_lines');
	}

}
