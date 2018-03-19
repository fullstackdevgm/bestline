<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFabricTypeCol extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fabric_types', function(Blueprint $table) {
		    $table->increments('id');
		    $table->string('type')->index();
		    $table->string('name');
		    $table->timestamps();
		});
		
		Schema::create('selected_fabric_types', function(Blueprint $table) {
		    $table->increments('id');
		    $table->integer('fabric_id')->unsigned();
		    $table->integer('type_id')->unsigned();
		    $table->timestamps();
		    
		    $table->foreign('fabric_id')
		          ->references('id')
		          ->on('fabrics');
		    
		    $table->foreign('type_id')
		          ->references('id')
		          ->on('fabric_types');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::unprepared("SET foreign_key_checks=0");
		Schema::drop('fabric_types');
		Schema::drop('selected_fabric_types');
		DB::unprepared("SET foreign_key_checks=1");
	}

}
