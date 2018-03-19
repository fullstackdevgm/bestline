<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsAssemblerNoteToFinalizedOrderLineOptions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('finalized_order_line_options', function(Blueprint $table)
		{
			$table->boolean('is_assembler_note')->default(FALSE);
			$table->string('assembler_note_text')->nullable();

			//since I'm making changes, I should make stuff nullable
			DB::statement("
				ALTER TABLE `finalized_order_line_options`
					ALTER `suboption_pricing_type` DROP DEFAULT;
			");
			DB::statement("
				ALTER TABLE `finalized_order_line_options`
					CHANGE COLUMN `suboption_pricing_type` `suboption_pricing_type` VARCHAR(255) NULL;
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
		Schema::table('finalized_order_line_options', function(Blueprint $table)
		{
			$table->dropColumn('is_assembler_note');
			//$table->dropColumn('assembler_note_text');
		});
	}

}
