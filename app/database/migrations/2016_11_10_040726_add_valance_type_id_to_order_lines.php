<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValanceTypeIdToOrderLines extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_lines', function(Blueprint $table)
		{
			$table->integer('valance_type_id')->unsigned()->nullable()->after('has_valance');
			$table->foreign('valance_type_id')
				->references('id')
				->on('valance_types')
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
		Schema::table('order_lines', function(Blueprint $table)
		{
			$table->dropForeign('order_lines_valance_type_id_foreign');
			$table->dropColumn('valance_type_id');
		});
	}

}
