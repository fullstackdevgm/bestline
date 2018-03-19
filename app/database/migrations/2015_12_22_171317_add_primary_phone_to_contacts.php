<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrimaryPhoneToContacts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('contacts', function(Blueprint $table)
		{
			$table->integer('primary_phone_number_id')->unsigned()->nullable();
			$table->foreign('primary_phone_number_id')
				->references('id')
				->on('phone_numbers')
				->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('contacts', function(Blueprint $table)
		{
			$table->dropForeign('contacts_primary_phone_number_id_foreign');
			$table->dropColumn('primary_phone_number_id');
		});
	}

}
