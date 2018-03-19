<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAndModifyFieldsFinalizedOrderFabrics extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('finalized_order_fabrics', function(Blueprint $table)
		{
			DB::statement("
				ALTER TABLE `finalized_order_fabrics`
					ALTER `fabric_width` DROP DEFAULT,
					ALTER `fabric_repeat` DROP DEFAULT,
					ALTER `fabric_margin` DROP DEFAULT,
					ALTER `fabric_unit_price` DROP DEFAULT,
					ALTER `fabric_pricing_type` DROP DEFAULT,
					ALTER `fabric_type` DROP DEFAULT,
					ALTER `fabric_pattern` DROP DEFAULT,
					ALTER `fabric_color` DROP DEFAULT;
			");
			DB::statement("
				ALTER TABLE `finalized_order_fabrics`
					CHANGE COLUMN `fabric_width` `fabric_width` DECIMAL(8,3) NULL,
					CHANGE COLUMN `fabric_repeat` `fabric_repeat` DECIMAL(8,3) NULL,
					CHANGE COLUMN `fabric_margin` `fabric_margin` DECIMAL(8,3) NULL,
					CHANGE COLUMN `fabric_unit_price` `fabric_unit_price` DECIMAL(8,2) NULL,
					CHANGE COLUMN `fabric_pricing_type` `fabric_pricing_type` VARCHAR(255) NULL,
					CHANGE COLUMN `fabric_type` `fabric_type` VARCHAR(255) NULL,
					CHANGE COLUMN `fabric_pattern` `fabric_pattern` VARCHAR(255) NULL,
					CHANGE COLUMN `fabric_color` `fabric_color` VARCHAR(255) NULL;
			");

			$table->string('sidemark')->nullable();
			$table->string('image')->nullable();
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
			//once you go null, trying to revert could cause migration errors because some values may already be null

			$table->dropColumn('sidemark');
			$table->dropColumn('image');
		});
	}

}
