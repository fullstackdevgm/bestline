<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlteredContactsToAddPrimaryEmail extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('contacts', function(Blueprint $table)
		{
			$table->integer('primary_email_id')->unsigned()->nullable();

			$table->foreign('primary_email_id')
				->references('id')
				->on('emails')
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
			$table->dropForeign('contacts_primary_email_id_foreign');
			$table->dropColumn('primary_email_id');
		});
	}

}
