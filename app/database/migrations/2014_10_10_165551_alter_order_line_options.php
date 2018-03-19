<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderLineOptions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_line_options', function(Blueprint $table) {
		    $table->dropColumn('pricing_type');
		    $table->dropColumn('min_charge');
		    $table->dropColumn('pricing_value');
		    $table->decimal('price', 8, 2);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_line_options', function(Blueprint $table) {
		    $table->dropColumn('price');
		    $table->enum('pricing_type', PricingType::getTypes(false))->nullable();
		    $table->decimal('min_charge', 8, 2)->nullable();
		    $table->decimal('pricing_value', 6, 3)->nullable();
		});
	}

}
