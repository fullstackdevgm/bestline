<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyOptions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('options', function(Blueprint $table)
		{
			$table->dropColumn('is_assembler_note');
			$table->dropColumn('assembler_note_text');
			$table->string('assembler_note')->nullable()->after('notes');
			$table->string('seamstress_note')->nullable()->after('assembler_note');
			$table->string('embellisher_note')->nullable()->after('seamstress_note');
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
			$table->boolean('is_assembler_note')->default(FALSE);
			$table->string('assembler_note_text')->nullable();
			$table->dropColumn('assembler_note');
			$table->dropColumn('seamstress_note');
			$table->dropColumn('embellisher_note');
		});
	}

}
