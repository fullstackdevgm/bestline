<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixNullProblem extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    DB::statement('ALTER TABLE `finalized_order_fabrics` MODIFY `fabric_com` tinyint(1) DEFAULT 0;');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    DB::statement('ALTER TABLE `finalized_order_fabrics` MODIFY `fabric_com` tinyint(1);');
	}

}
