<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinalizedOrderDefaultOptions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('finalized_order_default_options', function(Blueprint $table)
		{
			$table->increments('id');
		    $table->integer('finalized_order_id')->unsigned();
		    $table->integer('option_id')->unsigned();
		    $table->integer('sub_option_id')->unsigned();
            $table->timestamps();
            
            $table->foreign('finalized_order_id')
                  ->references('id')
                  ->on('finalized_orders')
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
		Schema::drop('finalized_order_default_options');
	}

}
