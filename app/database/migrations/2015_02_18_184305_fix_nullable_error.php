<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixNullableError extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    DB::statement('ALTER TABLE `finalized_order_fabrics` MODIFY `fabric_formula` VARCHAR(255) NULL;');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    DB::statement('ALTER TABLE `finalized_order_fabrics` MODIFY `fabric_formula` VARCHAR(255) NULL;');
	}

}
