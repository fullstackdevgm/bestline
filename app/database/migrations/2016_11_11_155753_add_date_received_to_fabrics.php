<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateReceivedToFabrics extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('fabrics', function(Blueprint $table)
		{
			$table->date('date_received')->after('unit_price')->nullable();
			$table->string('ticket_number')->after('owner_company_id')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('fabrics', function(Blueprint $table)
		{
			$table->dropColumn('date_received');
			$table->dropColumn('ticket_number');
		});
	}

}
