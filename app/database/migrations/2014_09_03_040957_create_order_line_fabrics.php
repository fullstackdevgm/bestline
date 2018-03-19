<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderLineFabrics extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_line_fabrics', function(Blueprint $table) {
		    $table->increments('id');
		    $table->integer('order_line_id')->unsigned();
		    $table->integer('fabric_id')->unsigned();
		    $table->enum('fabric_type', array('face', 'lining'));
		    $table->decimal('unit_price');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('order_line_fabrics');
	}

}