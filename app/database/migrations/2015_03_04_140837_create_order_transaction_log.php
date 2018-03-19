<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTransactionLog extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_transaction_logs', function(Blueprint $table) {
		    $table->increments('id');
		    $table->integer('user_id')->unsigned();
		    $table->integer('order_id')->unsigned();
		    $table->integer('order_line_id')->unsigned()->nullable();
		    $table->string('event');
		    $table->string('message');
		    $table->longText('data');
		    $table->timestamps();
		    
		    $table->foreign('user_id')
		          ->references('id')
		          ->on('users');
		          
		    $table->foreign('order_line_id')
		          ->references('id')
		          ->on('order_lines')
		          ->onDelete('cascade');
		    
		    $table->foreign('order_id')
		          ->references('id')
		          ->on('orders')
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
		Schema::drop('order_transaction_logs');
	}

}
