<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinalizedOrderFabrics extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('finalized_order_fabrics', function(Blueprint $table) {
		    $table->increments('id');
		    $table->integer('finalized_order_id')->unsigned();
		    $table->string('fabric_name');
		    $table->decimal('fabric_width', 8, 2);
		    $table->integer('fabric_repeat');
		    $table->decimal('fabric_margin', 8, 2);
		    $table->decimal('fabric_unit_price', 8, 2);
		    $table->boolean('fabric_com');
		    $table->string('fabric_pricing_type');
		    $table->string('fabric_formula');
		    $table->string('fabric_type');
		    $table->timestamps();
		    
		    $table->foreign('finalized_order_id')
		          ->references('id')
		          ->on('finalized_orders');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('finalized_order_fabrics');
	}

}
