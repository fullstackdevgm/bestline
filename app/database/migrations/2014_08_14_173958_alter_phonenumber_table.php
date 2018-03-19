<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterPhonenumberTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('phone_numbers', function(Blueprint $table) {
			DB::statement('ALTER TABLE phone_numbers CHANGE numbers number VARCHAR(255)');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('phone_numbers', function(Blueprint $table) {
			DB::statement('ALTER TABLE phone_numbers CHANGE number numbers VARCHAR(255)');
		});
	}

}