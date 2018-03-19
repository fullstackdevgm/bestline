<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderFinalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('finalized_orders', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();

			//foreign keys
			$table->integer('order_id')->unsigned()->nullable();
			$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

			$table->decimal('subtotal', 8, 2)->nullable();
			$table->decimal('discount_total', 8, 2)->nullable();
			$table->decimal('rush_total', 8, 2)->nullable();
			$table->decimal('total', 8, 2)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('finalized_orders');
	}

}
