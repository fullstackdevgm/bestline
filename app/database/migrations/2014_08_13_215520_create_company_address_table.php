<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompanyAddressTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public static function up()
	{
		Schema::create('company_addresses', function(Blueprint $table) {
			$table->integer('company_id')->unsigned();
			$table->integer('address_id')->unsigned();
			
			$table->foreign('company_id')
					->references('id')
					->on('companies')
					->onDelete('cascade');
			
			$table->foreign('address_id')
					->references('id')
					->on('addresses')
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
		Schema::drop('company_addresses');
	}

}