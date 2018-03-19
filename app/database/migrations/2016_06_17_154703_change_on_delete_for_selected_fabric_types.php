<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeOnDeleteForSelectedFabricTypes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('selected_fabric_types', function(Blueprint $table)
		{
			$table->dropForeign('selected_fabric_types_fabric_id_foreign');

			$table->foreign('fabric_id')
			      ->references('id')
			      ->on('fabrics')
			      ->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('selected_fabric_types', function(Blueprint $table)
		{
			//doesn't make sense to undo this
		});
	}

}
