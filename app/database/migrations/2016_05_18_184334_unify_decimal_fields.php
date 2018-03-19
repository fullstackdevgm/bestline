<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UnifyDecimalFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("
			ALTER TABLE `options`
				ALTER `pricing_value` DROP DEFAULT;
		");
		DB::statement("
			ALTER TABLE `options`
				CHANGE COLUMN `pricing_value` `pricing_value` DECIMAL(8,2) NULL;
		");

		DB::statement("
			ALTER TABLE `order_default_options`
				ALTER `pricing_value` DROP DEFAULT;
		");
		DB::statement("
			ALTER TABLE `order_default_options`
				CHANGE COLUMN `pricing_value` `pricing_value` DECIMAL(8,2) NULL;
		");

		DB::statement("
			ALTER TABLE `order_lines`
				ALTER `width` DROP DEFAULT,
				ALTER `height` DROP DEFAULT,
				ALTER `headerboard` DROP DEFAULT,
				ALTER `return` DROP DEFAULT;
		");
		DB::statement("
			ALTER TABLE `order_lines`
				CHANGE COLUMN `width` `width` DECIMAL(8,3) NULL,
				CHANGE COLUMN `height` `height` DECIMAL(8,3) NULL,
				CHANGE COLUMN `headerboard` `headerboard` DECIMAL(8,3) NULL,
				CHANGE COLUMN `return` `return` DECIMAL(8,3) NULL;
		");

		DB::statement("
			ALTER TABLE `products`
				ALTER `price_plus_percentage` DROP DEFAULT,
				ALTER `ring_from_edge` DROP DEFAULT,
				ALTER `base_price` DROP DEFAULT;
		");
		DB::statement("
			ALTER TABLE `products`
				CHANGE COLUMN `price_plus_percentage` `price_plus_percentage` DECIMAL(8,2) NULL,
				CHANGE COLUMN `ring_from_edge` `ring_from_edge` DECIMAL(8,3) NULL,
				CHANGE COLUMN `base_price` `base_price` DECIMAL(8,2) NULL;
		");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("
			ALTER TABLE `options`
				ALTER `pricing_value` DROP DEFAULT;
		");
		DB::statement("
			ALTER TABLE `options`
				CHANGE COLUMN `pricing_value` `pricing_value` DECIMAL(6,3) NULL;
		");

		DB::statement("
			ALTER TABLE `order_default_options`
				ALTER `pricing_value` DROP DEFAULT;
		");
		DB::statement("
			ALTER TABLE `order_default_options`
				CHANGE COLUMN `pricing_value` `pricing_value` DECIMAL(6,3) NULL;
		");

		DB::statement("
			ALTER TABLE `order_lines`
				ALTER `width` DROP DEFAULT,
				ALTER `height` DROP DEFAULT,
				ALTER `headerboard` DROP DEFAULT,
				ALTER `return` DROP DEFAULT;
		");
		DB::statement("
			ALTER TABLE `order_lines`
				CHANGE COLUMN `width` `width` DECIMAL(6,3) NULL,
				CHANGE COLUMN `height` `height` DECIMAL(6,3) NULL,
				CHANGE COLUMN `headerboard` `headerboard` DECIMAL(6,3) NULL,
				CHANGE COLUMN `return` `return` DECIMAL(6,3) NULL;
		");

		DB::statement("
			ALTER TABLE `products`
				ALTER `price_plus_percentage` DROP DEFAULT,
				ALTER `ring_from_edge` DROP DEFAULT,
				ALTER `base_price` DROP DEFAULT;
		");
		DB::statement("
			ALTER TABLE `products`
				CHANGE COLUMN `price_plus_percentage` `price_plus_percentage` DECIMAL(6,3) NULL,
				CHANGE COLUMN `ring_from_edge` `ring_from_edge` DECIMAL(6,3) NULL,
				CHANGE COLUMN `base_price` `base_price` DECIMAL(6,2) NULL;
		");
	}

}
