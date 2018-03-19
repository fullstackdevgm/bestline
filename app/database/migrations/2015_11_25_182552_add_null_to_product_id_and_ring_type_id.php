<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullToProductIdAndRingTypeId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::table('orders', function(Blueprint $table){

			DB::statement("ALTER TABLE `orders` DROP FOREIGN KEY `orders_ring_type_id_foreign`;");
			DB::statement("ALTER TABLE `orders` DROP FOREIGN KEY `orders_product_id_foreign`;");

            DB::statement("ALTER TABLE `orders` ALTER `product_id` DROP DEFAULT;");
            DB::statement('ALTER TABLE `orders` CHANGE COLUMN `product_id` `product_id` INT(10) UNSIGNED NULL AFTER `shipping_amount`;');

            DB::statement("ALTER TABLE `orders` ALTER `ring_type_id` DROP DEFAULT;");
            DB::statement('ALTER TABLE `orders` CHANGE COLUMN `ring_type_id` `ring_type_id` INT(10) UNSIGNED NULL AFTER `product_id`;');

            $table->foreign('product_id')
                  ->references('id')
                  ->on('products');

            $table->foreign('ring_type_id')
		      ->references('id')
		      ->on('ring_types');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

		Schema::table('orders', function(Blueprint $table){

			DB::statement("ALTER TABLE `orders` DROP FOREIGN KEY `orders_product_id_foreign`;");
			DB::statement("ALTER TABLE `orders` DROP FOREIGN KEY `orders_ring_type_id_foreign`;");

			DB::statement("ALTER TABLE `orders` ALTER `product_id` DROP DEFAULT;");
			DB::statement('ALTER TABLE `orders` CHANGE COLUMN `product_id` `product_id` INT(10) UNSIGNED NOT NULL AFTER `shipping_amount`;');

            DB::statement("ALTER TABLE `orders` ALTER `ring_type_id` DROP DEFAULT;");
            DB::statement('ALTER TABLE `orders` CHANGE COLUMN `ring_type_id` `ring_type_id` INT(10) UNSIGNED NOT NULL AFTER `product_id`;');

            DB::table('orders')->where('product_id', '=', 'NULL')->delete();

            $table->foreign('product_id')
                  ->references('id')
                  ->on('products');

            $table->foreign('ring_type_id')
		      ->references('id')
		      ->on('ring_types');
        });
	}

}
