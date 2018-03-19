<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsSkirtEmbellishmentToFinalizedOrderLineOptions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('finalized_order_line_options', function(Blueprint $table)
		{
			$table->boolean('is_skirt_embellishment')->default(FALSE);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('finalized_order_line_options', function(Blueprint $table)
		{
			$table->dropColumn('is_skirt_embellishment');
		});
	}

}
