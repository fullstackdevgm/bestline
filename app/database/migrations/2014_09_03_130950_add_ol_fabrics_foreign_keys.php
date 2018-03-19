<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddOlFabricsForeignKeys extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::table('order_line_fabrics', function(Blueprint $table) {
	        $table->foreign('order_line_id')
	              ->references('id')
	              ->on('order_lines')
	              ->onDelete('cascade');
	        
	        $table->foreign('fabric_id')
	              ->references('id')
	              ->on('fabrics')
	              ->onDelete('cascade');
	    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}