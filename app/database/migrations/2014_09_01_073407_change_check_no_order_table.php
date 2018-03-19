<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ChangeCheckNoOrderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('orders', function(Blueprint $table) {
		    $table->dropColumn('deposit_check_no');
		});
		
	    Schema::table('orders', function(Blueprint $table) {
	        $table->integer('deposit_check_no')->default(0);
	    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    Schema::table('orders', function(Blueprint $table) {
	        $table->dropColumn('deposit_check_no');
	    });
	    
        Schema::table('orders', function(Blueprint $table) {
            $table->decimal('deposit_check_no');
        });
	}

}