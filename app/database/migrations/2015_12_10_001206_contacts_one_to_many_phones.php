<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContactsOneToManyPhones extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('phone_numbers', function(Blueprint $table)
		{
			$table->integer('contact_id')->unsigned()->nullable();

			$table->foreign('contact_id')
				->references('id')
				->on('contacts')
				->onDelete('cascade');
		});

		CreateContactPhoneTable::down();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('phone_numbers', function(Blueprint $table)
		{
			$table->dropForeign('phone_numbers_contact_id_foreign');
			$table->dropColumn('contact_id');
		});

		CreateContactPhoneTable::up();
	}

}
