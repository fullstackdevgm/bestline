<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeShippingTypesNullable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    DB::statement('ALTER TABLE `finalized_orders` MODIFY `sm_formula` VARCHAR(255) NULL;');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    DB::statement('ALTER TABLE `finalized_orders` MODIFY `sm_formula` VARCHAR(255);');
	}

}
