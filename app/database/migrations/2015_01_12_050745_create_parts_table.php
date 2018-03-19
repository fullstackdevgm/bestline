<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('parts', function(Blueprint $table) {
		    $table->increments('id');
		    $table->string('name');
		    $table->text('description');
		    $table->integer('minimum_qty')->unsigned();
		    $table->timestamps();
		});
		
	    Schema::create('parts_inventory', function(Blueprint $table) {
	        $table->increments('id');
	        $table->integer('part_id')->unsigned();
	        $table->integer('quantity')->unsigned();
	        $table->integer('adjustment')->default(0);
	        $table->string('reason');
	        $table->integer('by_user_id')->unsigned()->nullable();
	    
	        $table->timestamps();
	    
	        $table->foreign('part_id')
	              ->references('id')
	              ->on('parts')
	              ->onDelete('cascade');
	    
	        $table->foreign('by_user_id')
	              ->references('id')
	              ->on('users');
	    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('parts_inventory');
		Schema::drop('parts');
	}

}
