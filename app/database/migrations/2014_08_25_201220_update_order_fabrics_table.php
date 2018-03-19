<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateOrderFabricsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_fabrics', function(Blueprint $table) {
		    $table->enum('type', array('face', 'lining', 'additional'))->default('additional');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    Schema::table('order_fabrics', function(Blueprint $table) {
	        $table->dropColumn('type');
	    });
	}

}