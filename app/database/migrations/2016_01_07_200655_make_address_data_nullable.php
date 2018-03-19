<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeAddressDataNullable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('addresses', function(Blueprint $table)
		{
			DB::statement("
				ALTER TABLE `addresses`
					ALTER `first_name` DROP DEFAULT,
					ALTER `last_name` DROP DEFAULT,
					ALTER `address1` DROP DEFAULT,
					ALTER `address2` DROP DEFAULT,
					ALTER `city` DROP DEFAULT,
					ALTER `state` DROP DEFAULT,
					ALTER `zip` DROP DEFAULT,
					ALTER `type` DROP DEFAULT;
			");
			DB::statement("
				ALTER TABLE `addresses`
					CHANGE COLUMN `first_name` `first_name` VARCHAR(255) NULL,
					CHANGE COLUMN `last_name` `last_name` VARCHAR(255) NULL,
					CHANGE COLUMN `address1` `address1` VARCHAR(255) NULL,
					CHANGE COLUMN `address2` `address2` VARCHAR(255) NULL,
					CHANGE COLUMN `city` `city` VARCHAR(255) NULL,
					CHANGE COLUMN `state` `state` VARCHAR(255) NULL,
					CHANGE COLUMN `zip` `zip` VARCHAR(255) NULL,
					CHANGE COLUMN `type` `type` ENUM('billing','shipping') NULL;
			");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('addresses', function(Blueprint $table)
		{
			DB::statement("
				ALTER TABLE `addresses`
					ALTER `first_name` DROP DEFAULT,
					ALTER `last_name` DROP DEFAULT,
					ALTER `address1` DROP DEFAULT,
					ALTER `address2` DROP DEFAULT,
					ALTER `city` DROP DEFAULT,
					ALTER `state` DROP DEFAULT,
					ALTER `zip` DROP DEFAULT,
					ALTER `type` DROP DEFAULT;
			");
			DB::statement("
				ALTER TABLE `addresses`
					CHANGE COLUMN `first_name` `first_name` VARCHAR(255) NOT NULL,
					CHANGE COLUMN `last_name` `last_name` VARCHAR(255) NOT NULL,
					CHANGE COLUMN `address1` `address1` VARCHAR(255) NOT NULL,
					CHANGE COLUMN `address2` `address2` VARCHAR(255) NOT NULL,
					CHANGE COLUMN `city` `city` VARCHAR(255) NOT NULL,
					CHANGE COLUMN `state` `state` VARCHAR(255) NOT NULL,
					CHANGE COLUMN `zip` `zip` VARCHAR(255) NOT NULL,
					CHANGE COLUMN `type` `type` ENUM('billing','shipping') NOT NULL;
			");
		});
	}

}
