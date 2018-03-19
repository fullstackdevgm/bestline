<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinalizedOrderLineOptions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('finalized_order_line_options', function(Blueprint $table) {
		    $table->increments('id');
		    $table->integer('finalized_order_line_id')->unsigned();
		    $table->string('option_name');
		    $table->string('suboption_name');
		    $table->string('suboption_pricing_type');
		    $table->decimal('suboption_min_charge', 8, 2)->nullable();
		    $table->decimal('suboption_pricing_value', 8, 3);
		    $table->decimal('price', 8, 2);
		    $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('finalized_order_line_options');
	}

}
