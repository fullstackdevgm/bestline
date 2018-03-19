<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixMoreNullIssues extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE `finalized_orders` MODIFY `discount_percent` int(11) NULL;');
		DB::statement('ALTER TABLE `finalized_orders` MODIFY `rush_percent` int(11) NULL;');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    DB::statement('ALTER TABLE `finalized_orders` MODIFY `discount_percent` int(11);');
	    DB::statement('ALTER TABLE `finalized_orders` MODIFY `rush_percent` int(11);');
	}

}
