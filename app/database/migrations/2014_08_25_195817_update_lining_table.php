<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateLiningTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('lining_colors');
		Schema::create('linings', function(Blueprint $table) {
		    $table->increments('id');
		    $table->string('code');
		    $table->string('description');
		    $table->enum('pricing_type', array('sqft', 'linear', 'flat'));
		    $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('linings');
		Schema::create('lining_colors', function(Blueprint $table) {
		    $table->increments('id');
		    $table->string('code');
		    $table->string('description');
		});
	}

}