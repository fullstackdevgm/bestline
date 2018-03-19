<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContactPhoneTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public static function up()
	{
		Schema::create('contact_phones', function(Blueprint $table) {
			$table->integer('phone_id')->unsigned();
			$table->integer('contact_id')->unsigned();
			
			$table->foreign('phone_id')
					->references('id')
					->on('phone_numbers')
					->onDelete('cascade');
			
			$table->foreign('contact_id')
					->references('id')
					->on('contacts')
					->onDelete('cascade');
			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public static function down()
	{
		Schema::drop('contact_phones');
	}

}