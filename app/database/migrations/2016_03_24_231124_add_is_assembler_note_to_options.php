<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsAssemblerNoteToOptions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('options', function(Blueprint $table)
		{
			$table->boolean('is_assembler_note')->default(FALSE);
			$table->string('assembler_note_text')->nullable();

			//since I'm making changes, I should make stuff nullable
			DB::statement("
				ALTER TABLE `options`
					ALTER `notes` DROP DEFAULT,
					ALTER `tier_formula` DROP DEFAULT;
			");
			DB::statement("
				ALTER TABLE `options`
					CHANGE COLUMN `notes` `notes` VARCHAR(255) NULL,
					CHANGE COLUMN `tier_formula` `tier_formula` VARCHAR(255) NULL;
			");
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
			//can't undo nullable without errors

			$table->dropColumn('is_assembler_note');
			//$table->dropColumn('assembler_note_text');
		});
	}

}
