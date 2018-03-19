<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakePhoneDataNullable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('phone_numbers', function(Blueprint $table)
		{
			DB::statement("
				ALTER TABLE `phone_numbers`
					ALTER `number` DROP DEFAULT,
					ALTER `type` DROP DEFAULT;
			");
			DB::statement("
				ALTER TABLE `phone_numbers`
					CHANGE COLUMN `number` `number` VARCHAR(255) NOT NULL,
					CHANGE COLUMN `type` `type` ENUM('voice','fax') NULL;
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
		Schema::table('phone_numbers', function(Blueprint $table)
		{
			DB::statement("
				ALTER TABLE `phone_numbers`
					ALTER `number` DROP DEFAULT,
					ALTER `type` DROP DEFAULT;
			");
			DB::statement("
				ALTER TABLE `phone_numbers`
					CHANGE COLUMN `number` `number` VARCHAR(255) NULL,
					CHANGE COLUMN `type` `type` ENUM('voice','fax') NOT NULL;
			");
		});
	}

}
