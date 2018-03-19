<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderDataIntegrity extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('orders', function(Blueprint $table)
		{
			DB::statement("ALTER TABLE `orders` DROP FOREIGN KEY `orders_customer_type_id_foreign`;");
			DB::statement("ALTER TABLE `orders` DROP FOREIGN KEY `orders_shipping_address_id_foreign`;");
			DB::statement("ALTER TABLE `orders` DROP FOREIGN KEY `orders_billing_address_id_foreign`;");
			

			DB::statement("
				ALTER TABLE `orders`
					ALTER `customer_type_id` DROP DEFAULT,
					ALTER `shipping_address_id` DROP DEFAULT,
					ALTER `billing_address_id` DROP DEFAULT,
					ALTER `phone_number` DROP DEFAULT,
					ALTER `fax_number` DROP DEFAULT;
			");

			DB::statement("
				ALTER TABLE `orders`
					CHANGE COLUMN `notes` `notes` TEXT NULL,
					CHANGE COLUMN `ticket_notes` `ticket_notes` TEXT NULL,
					CHANGE COLUMN `invoice_notes` `invoice_notes` TEXT NULL,
					CHANGE COLUMN `deposit_amount` `deposit_amount` DECIMAL(8,2) NULL DEFAULT '0.00',
					CHANGE COLUMN `customer_type_id` `customer_type_id` INT(10) UNSIGNED NULL,
					CHANGE COLUMN `shipping_address_id` `shipping_address_id` INT(10) UNSIGNED NULL,
					CHANGE COLUMN `billing_address_id` `billing_address_id` INT(10) UNSIGNED NULL,
					CHANGE COLUMN `phone_number` `phone_number` VARCHAR(255) NULL,
					CHANGE COLUMN `fax_number` `fax_number` VARCHAR(255) NULL,
					CHANGE COLUMN `deposit_check_no` `deposit_check_no` INT(11) NULL DEFAULT '0',
					CHANGE COLUMN `boxing_cost` `boxing_cost` DECIMAL(8,2) NULL DEFAULT '0.00';
			");

			$table->foreign('customer_type_id')
		    	->references('id')
		    	->on('customer_types')
		    	->onDelete('set null');
			$table->foreign('shipping_address_id')
		    	->references('id')
		    	->on('addresses')
		    	->onDelete('set null');
			$table->foreign('billing_address_id')
		    	->references('id')
		    	->on('addresses')
		    	->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('orders', function(Blueprint $table)
		{
			//cannot go back because some values could be null and it would throw an error on reset
		});
	}

}
