<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIsSkirtEmbellishmentToIsEmbellishmentOption extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('options', function(Blueprint $table)
		{
			$table->dropColumn('is_skirt_embellishment');
			$table->boolean('is_embellishment_option')->default(FALSE);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('options', function(Blueprint $table)
		{
			$table->dropColumn('is_embellishment_option');
			$table->boolean('is_skirt_embellishment')->default(FALSE);
		});
	}

}
