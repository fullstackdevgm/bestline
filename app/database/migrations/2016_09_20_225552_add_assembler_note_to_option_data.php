<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssemblerNoteToOptionData extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('option_data', function(Blueprint $table)
		{
			$table->string('assembler_note')->nullable()->after('degrees');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('option_data', function(Blueprint $table)
		{
			$table->dropColumn('assembler_note');
		});
	}

}
