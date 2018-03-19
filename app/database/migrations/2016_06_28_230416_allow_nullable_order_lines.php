<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllowNullableOrderLines extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		
		DB::statement("
			ALTER TABLE `order_lines`
				ALTER `cord_length` DROP DEFAULT,
				ALTER `product_id` DROP DEFAULT,
				ALTER `cord_position_id` DROP DEFAULT,
				ALTER `hardware_id` DROP DEFAULT,
				ALTER `mount_id` DROP DEFAULT,
				ALTER `valance_width` DROP DEFAULT,
				ALTER `valance_height` DROP DEFAULT,
				ALTER `valance_depth` DROP DEFAULT;
		");
		DB::statement("
			ALTER TABLE `order_lines`
				CHANGE COLUMN `cord_length` `cord_length` INT(11) NULL AFTER `id`,
				CHANGE COLUMN `product_id` `product_id` INT(10) UNSIGNED NULL AFTER `cord_length`,
				CHANGE COLUMN `cord_position_id` `cord_position_id` INT(10) UNSIGNED NULL AFTER `product_id`,
				CHANGE COLUMN `hardware_id` `hardware_id` INT(10) UNSIGNED NULL AFTER `cord_position_id`,
				CHANGE COLUMN `mount_id` `mount_id` INT(10) UNSIGNED NULL AFTER `hardware_id`,
				CHANGE COLUMN `valance_width` `valance_width` DECIMAL(8,3) NULL AFTER `return`,
				CHANGE COLUMN `valance_height` `valance_height` DECIMAL(8,3) NULL AFTER `valance_width`,
				CHANGE COLUMN `valance_depth` `valance_depth` DECIMAL(8,3) NULL AFTER `valance_height`;
		");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//once you go null, trying to revert could cause migration errors because some values may already be null
	}

}
