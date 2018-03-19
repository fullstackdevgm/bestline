<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllowNullableFinalizedOrders extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('finalized_orders', function(Blueprint $table)
		{
			DB::statement("
				ALTER TABLE `finalized_orders`
					ALTER `notes` DROP DEFAULT,
					ALTER `ticket_notes` DROP DEFAULT,
					ALTER `invoice_notes` DROP DEFAULT,
					ALTER `purchase_order` DROP DEFAULT,
					ALTER `deposit_amount` DROP DEFAULT,
					ALTER `ct_name` DROP DEFAULT,
					ALTER `ct_description` DROP DEFAULT,
					ALTER `company_website` DROP DEFAULT,
					ALTER `company_notes` DROP DEFAULT,
					ALTER `company_account_no` DROP DEFAULT,
					ALTER `company_area` DROP DEFAULT,
					ALTER `company_name` DROP DEFAULT,
					ALTER `sa_first_name` DROP DEFAULT,
					ALTER `sa_last_name` DROP DEFAULT,
					ALTER `sa_address1` DROP DEFAULT,
					ALTER `sa_address2` DROP DEFAULT,
					ALTER `sa_city` DROP DEFAULT,
					ALTER `sa_state` DROP DEFAULT,
					ALTER `sa_zip` DROP DEFAULT,
					ALTER `ba_first_name` DROP DEFAULT,
					ALTER `ba_last_name` DROP DEFAULT,
					ALTER `ba_address1` DROP DEFAULT,
					ALTER `ba_address2` DROP DEFAULT,
					ALTER `ba_city` DROP DEFAULT,
					ALTER `ba_state` DROP DEFAULT,
					ALTER `ba_zip` DROP DEFAULT,
					ALTER `phone_number` DROP DEFAULT,
					ALTER `fax_number` DROP DEFAULT,
					ALTER `deposit_check_no` DROP DEFAULT,
					ALTER `boxing_cost` DROP DEFAULT;
			");
			DB::statement("
				ALTER TABLE `finalized_orders`
					CHANGE COLUMN `notes` `notes` VARCHAR(255) NULL,
					CHANGE COLUMN `ticket_notes` `ticket_notes` VARCHAR(255) NULL,
					CHANGE COLUMN `invoice_notes` `invoice_notes` VARCHAR(255) NULL,
					CHANGE COLUMN `purchase_order` `purchase_order` VARCHAR(255) NULL,
					CHANGE COLUMN `deposit_amount` `deposit_amount` DECIMAL(8,2) NULL AFTER `purchase_order`,
					CHANGE COLUMN `ct_name` `ct_name` VARCHAR(255) NULL,
					CHANGE COLUMN `ct_description` `ct_description` VARCHAR(255) NULL,
					CHANGE COLUMN `company_website` `company_website` VARCHAR(255) NULL,
					CHANGE COLUMN `company_notes` `company_notes` VARCHAR(255) NULL,
					CHANGE COLUMN `company_account_no` `company_account_no` VARCHAR(255) NULL,
					CHANGE COLUMN `company_area` `company_area` VARCHAR(255) NULL,
					CHANGE COLUMN `company_name` `company_name` VARCHAR(255) NULL,
					CHANGE COLUMN `company_credit_term_notes` `company_credit_term_notes` TEXT NULL,
					CHANGE COLUMN `sa_first_name` `sa_first_name` VARCHAR(255) NULL,
					CHANGE COLUMN `sa_last_name` `sa_last_name` VARCHAR(255) NULL,
					CHANGE COLUMN `sa_address1` `sa_address1` VARCHAR(255) NULL,
					CHANGE COLUMN `sa_address2` `sa_address2` VARCHAR(255) NULL,
					CHANGE COLUMN `sa_city` `sa_city` VARCHAR(255) NULL,
					CHANGE COLUMN `sa_state` `sa_state` VARCHAR(255) NULL,
					CHANGE COLUMN `sa_zip` `sa_zip` VARCHAR(255) NULL,
					CHANGE COLUMN `ba_first_name` `ba_first_name` VARCHAR(255) NULL,
					CHANGE COLUMN `ba_last_name` `ba_last_name` VARCHAR(255) NULL,
					CHANGE COLUMN `ba_address1` `ba_address1` VARCHAR(255) NULL,
					CHANGE COLUMN `ba_address2` `ba_address2` VARCHAR(255) NULL,
					CHANGE COLUMN `ba_city` `ba_city` VARCHAR(255) NULL,
					CHANGE COLUMN `ba_state` `ba_state` VARCHAR(255) NULL,
					CHANGE COLUMN `ba_zip` `ba_zip` VARCHAR(255) NULL,
					CHANGE COLUMN `phone_number` `phone_number` VARCHAR(255) NULL,
					CHANGE COLUMN `fax_number` `fax_number` VARCHAR(255) NULL,
					CHANGE COLUMN `deposit_check_no` `deposit_check_no` INT(11) NULL,
					CHANGE COLUMN `boxing_cost` `boxing_cost` DECIMAL(8,2) NULL;
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
		Schema::table('finalized_orders', function(Blueprint $table)
		{
			//once you go null, trying to revert could cause migration errors because some values may already be null
		});
	}

}
