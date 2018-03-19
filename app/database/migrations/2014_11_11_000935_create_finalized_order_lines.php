<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinalizedOrderLines extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('finalized_order_lines', function(Blueprint $table) {
		    $table->increments('id');
		    $table->integer('finalized_order_id')->unsigned();
		    $table->integer('cord_length');
		    $table->decimal('width', 8, 2);
		    $table->decimal('height', 8, 2);
		    $table->decimal('headerboard', 8, 2);
		    $table->decimal('return', 8, 2);
		    $table->decimal('product_price_plus_percentage')->nullable();
		    $table->string('product_pricing_type');
		    $table->decimal('product_base_price', 8, 2);
		    $table->string('product_name');
		    $table->string('product_formula');
		    $table->string('tassel_code');
		    $table->string('tassel_description');
		    $table->string('cord_position_code');
		    $table->string('cord_position_description');
		    $table->string('hardware_code');
		    $table->string('hardware_description');
		    $table->string('hardware_formula')->nullable();
		    $table->string('mount_code');
		    $table->string('mount_description');
		    $table->decimal('shade_calc_price', 8, 2);
		    $table->decimal('fabric_calc_price', 8, 2);
		    $table->decimal('options_calc_price', 8, 2);
		    $table->decimal('line_total_price', 8, 2);
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
		Schema::drop('finalized_order_lines');
	}

}
