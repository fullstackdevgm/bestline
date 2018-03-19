<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompanyContactsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_contacts', function(Blueprint $table) {
			$table->integer('company_id')->unsigned();
			$table->integer('contact_id')->unsigned();
			
			$table->foreign('company_id')
					->references('id')
					->on('companies')
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
	public function down()
	{
		Schema::drop('company_contacts');
	}

}