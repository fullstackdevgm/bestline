<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderFabricOptions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_fabric_options', function(Blueprint $table)
		{
		    $table->increments('id');
		    $table->timestamps();
		    $table->integer('order_id')->unsigned()->nullable();
		    $table->integer('order_fabric_id')->unsigned()->nullable();
		    $table->integer('option_id')->unsigned()->nullable();
		    $table->integer('sub_option_id')->unsigned()->nullable();
            
            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->onDelete('cascade');

            $table->foreign('order_fabric_id')
                  ->references('id')
                  ->on('order_fabrics')
                  ->onDelete('cascade');
            
            $table->foreign('option_id')
                  ->references('id')
                  ->on('options')
                  ->onDelete('cascade');
            
            $table->foreign('sub_option_id')
                  ->references('id')
                  ->on('options')
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
		Schema::drop('order_fabric_options');
	}
}
