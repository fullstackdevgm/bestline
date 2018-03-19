<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderFabricsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_fabrics', function(Blueprint $table) {
		    
		    $table->increments('id');
		    $table->integer('order_id')->unsigned();
		    $table->integer('fabric_id')->unsigned();
		    
		    $table->foreign('order_id')
		          ->references('id')
		          ->on('orders')
		          ->onDelete('cascade');
		    
		    $table->foreign('fabric_id')
		          ->references('id')
		          ->on('fabrics');
		});
		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('order_fabrics');
	}

}