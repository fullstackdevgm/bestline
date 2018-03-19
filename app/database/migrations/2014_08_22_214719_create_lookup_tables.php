<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLookupTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cord_positions', function(Blueprint $table) {
			$table->increments('id');
			$table->string('code');
            $table->string('description');
		});

        Schema::create('height_to_soft_casuals', function(Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('description');
        });

        Schema::create('mounts', function(Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('description');
        });

        Schema::create('stringing_types', function(Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('description');
        });

        Schema::create('tassel_colors', function(Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('description');
        });

        Schema::create('lining_colors', function(Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('description');
        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cord_positions');

        Schema::drop('height_to_soft_casuals');

        Schema::drop('mounts');

        Schema::drop('stringing_types');

        Schema::drop('tassel_colors');

        Schema::drop('lining_colors');
	}

}
