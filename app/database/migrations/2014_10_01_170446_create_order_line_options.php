<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderLineOptions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_line_options', function(Blueprint $table) {
		    
		    $table->increments('id');
		    $table->integer('order_line_id')->unsigned();
		    $table->integer('option_id')->unsigned();
		    $table->integer('sub_option_id')->unsigned();
		    $table->enum('pricing_type', PricingType::getTypes(false))->nullable();
            $table->decimal('min_charge', 8, 2)->nullable();
            $table->decimal('pricing_value', 6, 3)->nullable();
            $table->timestamps();
            
            $table->foreign('order_line_id')
                  ->references('id')
                  ->on('order_lines')
                  ->onDelete('cascade');
            
            $table->foreign('option_id')
                  ->references('id')
                  ->on('options');
            
            $table->foreign('sub_option_id')
                  ->references('id')
                  ->on('options');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('order_line_options');
	}

}
