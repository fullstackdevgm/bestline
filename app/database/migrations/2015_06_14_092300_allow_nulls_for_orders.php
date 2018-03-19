<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllowNullsForOrders extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `orders` MODIFY COLUMN `date_shipped` DATE NULL;');
        DB::statement('ALTER TABLE `orders` MODIFY COLUMN `purchase_order` VARCHAR(255) NULL;');
        DB::statement('ALTER TABLE `orders` MODIFY COLUMN `shipping_amount` DECIMAL(8,2) NULL;');
        DB::statement('ALTER TABLE `finalized_orders` MODIFY COLUMN `sm_name` VARCHAR(255) NULL;');
        DB::statement('ALTER TABLE `finalized_orders` MODIFY COLUMN `sm_description` VARCHAR(255) NULL;');
        DB::statement('ALTER TABLE `finalized_orders` MODIFY COLUMN `subtotal_calc` DECIMAL(8,2) NULL;');
        DB::statement('ALTER TABLE `finalized_orders` MODIFY COLUMN `total_calc` DECIMAL(8,2) NULL;');
        DB::statement('ALTER TABLE `finalized_orders` MODIFY COLUMN `discount_calc` DECIMAL(8,2) NULL;');
        DB::statement('ALTER TABLE `finalized_orders` MODIFY COLUMN `rush_calc` DECIMAL(8,2) NULL;');
        DB::statement('ALTER TABLE `finalized_orders` MODIFY COLUMN `date_shipped` DATE NULL;');
        DB::statement('ALTER TABLE `finalized_orders` MODIFY COLUMN `shipping_amount` DECIMAL(8,2) NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This reversal may blow up if there are null values in any of these columns
        // so some manual assistance may be required.
        DB::statement('ALTER TABLE `orders` MODIFY COLUMN `date_shipped` DATE;');
        DB::statement('ALTER TABLE `orders` MODIFY COLUMN `purchase_order` VARCHAR(255);');
        DB::statement('ALTER TABLE `orders` MODIFY COLUMN `shipping_amount` DECIMAL(8,2);');
        DB::statement('ALTER TABLE `finalized_orders` MODIFY COLUMN `sm_name` VARCHAR(255);');
        DB::statement('ALTER TABLE `finalized_orders` MODIFY COLUMN `sm_description` VARCHAR(255);');
        DB::statement('ALTER TABLE `finalized_orders` MODIFY COLUMN `subtotal_calc` DECIMAL(8,2);');
        DB::statement('ALTER TABLE `finalized_orders` MODIFY COLUMN `total_calc` DECIMAL(8,2);');
        DB::statement('ALTER TABLE `finalized_orders` MODIFY COLUMN `discount_calc` DECIMAL(8,2);');
        DB::statement('ALTER TABLE `finalized_orders` MODIFY COLUMN `rush_calc` DECIMAL(8,2);');
        DB::statement('ALTER TABLE `finalized_orders` MODIFY COLUMN `date_shipped` DATE;');
        DB::statement('ALTER TABLE `finalized_orders` MODIFY COLUMN `shipping_amount` DECIMAL(8,2);');
    }

}
