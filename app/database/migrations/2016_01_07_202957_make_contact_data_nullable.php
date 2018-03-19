<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeContactDataNullable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('contacts', function(Blueprint $table)
		{
			DB::statement("
				ALTER TABLE `contacts`
					ALTER `title` DROP DEFAULT,
					ALTER `type` DROP DEFAULT;
			");
			DB::statement("
				ALTER TABLE `contacts`
					CHANGE COLUMN `title` `title` VARCHAR(255) NULL,
					CHANGE COLUMN `type` `type` ENUM('billing','shipping','generic') NULL;
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
		Schema::table('contacts', function(Blueprint $table)
		{
			DB::statement("
				ALTER TABLE `contacts`
					ALTER `title` DROP DEFAULT,
					ALTER `type` DROP DEFAULT;
			");
			DB::statement("
				ALTER TABLE `contacts`
					CHANGE COLUMN `title` `title` VARCHAR(255) NOT NULL,
					CHANGE COLUMN `type` `type` ENUM('billing','shipping','generic') NOT NULL;
			");
		});
	}

}
