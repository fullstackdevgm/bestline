<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderLineTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_lines', function(Blueprint $table) {
		    $table->increments('id');
		    $table->decimal('width', 3, 3);
		    $table->decimal('return', 3, 3);
		    $table->decimal('height', 3, 3);
		    $table->decimal('headerboard', 3, 3);
		    $table->integer('cord_length');
		    $table->integer('product_id')->unsigned();
		    $table->integer('tassel_id')->unsigned();
		    $table->integer('cord_position_id')->unsigned();
		    $table->integer('hardware_id')->unsigned();
		    $table->integer('mount_id')->unsigned();
		    $table->integer('order_id')->unsigned();
		    
		    $table->foreign('order_id')
		          ->references('id')
		          ->on('orders');
		    
		    $table->foreign('product_id')
		          ->references('id')
		          ->on('products');
		    
		    $table->foreign('tassel_id')
		          ->references('id')
		          ->on('tassel_colors');
		          
		    $table->foreign('cord_position_id')
		          ->references('id')
		          ->on('cord_positions');
		    
		    $table->foreign('hardware_id')
		          ->references('id')
		          ->on('stringing_types');
		    
		    $table->foreign('mount_id')
		          ->references('id')
		          ->on('mounts');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('order_lines');
	}

}