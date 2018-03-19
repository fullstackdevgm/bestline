<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddtFabricsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_addt_fabrics', function(Blueprint $table) {
		    $table->increments('id');
		    $table->integer('order_id')->unsigned();
		    $table->integer('fabric_id')->unsigned();
		    $table->timestamps();
		    
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
		Schema::drop('order_addt_fabrics');
	}

}
