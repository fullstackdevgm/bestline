<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFabricIdToFinalizedOrderFabrics extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('finalized_order_fabrics', function(Blueprint $table)
		{
			$table->integer('fabric_id')->unsigned()->nullable();
			$table->foreign('fabric_id')
				->references('id')
				->on('fabrics')
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
		Schema::table('finalized_order_fabrics', function(Blueprint $table)
		{
			$table->dropForeign('finalized_order_fabrics_fabric_id_foreign');
			$table->dropColumn('fabric_id');
		});
	}

}
