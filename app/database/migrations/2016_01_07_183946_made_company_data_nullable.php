<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MadeCompanyDataNullable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('companies', function(Blueprint $table)
		{

			//drop foreign key
			DB::statement("ALTER TABLE `companies` DROP FOREIGN KEY `companies_customer_type_id_foreign`;");

			DB::statement('
				ALTER TABLE `companies`	
					ALTER `website` DROP DEFAULT, 
					ALTER `account_no` DROP DEFAULT, 
					ALTER `area` DROP DEFAULT, 
					ALTER `customer_type_id` DROP DEFAULT,
					ALTER `credit_terms` DROP DEFAULT;
			');
			DB::statement("
				ALTER TABLE `companies` 
					CHANGE COLUMN `website` `website` VARCHAR(255) NULL,
					CHANGE COLUMN `notes` `notes` TEXT NULL,
					CHANGE COLUMN `account_no` `account_no` VARCHAR(255) NULL,
					CHANGE COLUMN `area` `area` VARCHAR(255) NULL,
					CHANGE COLUMN `customer_type_id` `customer_type_id` INT(10) UNSIGNED NULL,
					CHANGE COLUMN `credit_term_notes` `credit_term_notes` TEXT NULL,
					CHANGE COLUMN `credit_terms` `credit_terms` ENUM('cod','net10') NULL;
			");

			//re-add foreign key
			$table->foreign('customer_type_id')
		    	->references('id')
		    	->on('customer_types')
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
		Schema::table('companies', function(Blueprint $table)
		{
			DB::statement("ALTER TABLE `companies` DROP FOREIGN KEY `companies_customer_type_id_foreign`;");

			DB::statement('
				ALTER TABLE `companies`	
					ALTER `website` DROP DEFAULT, 
					ALTER `account_no` DROP DEFAULT, 
					ALTER `area` DROP DEFAULT, 
					ALTER `customer_type_id` DROP DEFAULT,
					ALTER `credit_terms` DROP DEFAULT;
			');

			DB::statement("
				ALTER TABLE `companies` 
					CHANGE COLUMN `website` `website` VARCHAR(255) NOT NULL,
					CHANGE COLUMN `notes` `notes` TEXT NOT NULL,
					CHANGE COLUMN `account_no` `account_no` VARCHAR(255) NOT NULL,
					CHANGE COLUMN `area` `area` VARCHAR(255) NOT NULL,
					CHANGE COLUMN `customer_type_id` `customer_type_id` INT(10) UNSIGNED NOT NULL,
					CHANGE COLUMN `credit_term_notes` `credit_term_notes` TEXT NOT NULL,
					CHANGE COLUMN `credit_terms` `credit_terms` ENUM('cod','net10') NOT NULL;
			");

			$table->foreign('customer_type_id')
				->references('id')
				->on('customer_types');
		});
	}

}
