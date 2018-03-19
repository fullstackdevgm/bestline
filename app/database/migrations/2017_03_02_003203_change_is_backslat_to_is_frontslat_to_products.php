<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIsBackslatToIsFrontslatToProducts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("
			ALTER TABLE `products`
				CHANGE COLUMN `is_backslat` `is_frontslat` TINYINT(1) DEFAULT 0 AFTER `is_casual`;
		");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("
			ALTER TABLE `products`
				CHANGE COLUMN `is_frontslat` `is_backslat` TINYINT(1) DEFAULT 0 AFTER `is_casual`;
		");
	}

}
