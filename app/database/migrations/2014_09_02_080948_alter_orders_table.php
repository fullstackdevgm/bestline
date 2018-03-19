<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('orders', function(Blueprint $table) {
		    $table->dropColumn('discount_formula');
		    $table->dropColumn('rush_cost_formula');
		    $table->dropColumn('boxing_cost_formula');
		    $table->integer('discount_percent')->unsigned()->nullable();
		    $table->integer('rush_percent')->unsigned()->nullable();
		    $table->decimal('boxing_cost')->default(0.00);
		});
		
		DB::statement("ALTER TABLE `orders` CHANGE COLUMN `deposit_amount` `deposit_amount` DECIMAL(8,2) NOT NULL DEFAULT 0.00");
		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('orders', function(Blueprint $table) {
		    $table->dropColumn('discount_percent');
		    $table->dropColumn('rush_percent');
		    $table->dropColumn('boxing_cost');
		    
		    $table->string('discount_formula');
		    $table->string('rush_cost_formula');
		    $table->string('boxing_cost_formula');
		});
		
		    DB::statement("ALTER TABLE `orders` CHANGE COLUMN `deposit_amount` `deposit_amount` DECIMAL(8,2) NOT NULL");
	}

}