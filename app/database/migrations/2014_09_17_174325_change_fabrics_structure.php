<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFabricsStructure extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::drop('order_line_fabrics');
	    
	    Schema::table('order_fabrics', function(Blueprint $table) {
	        $table->enum('fabric_type', array('face', 'lining'));
	        $table->decimal('unit_price', 8, 2);
	        $table->dropColumn('type');
	    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::create('order_line_fabrics', function(Blueprint $table) {
		    $table->increments('id');
		    $table->integer('order_line_id')->unsigned();
		    $table->integer('fabric_id')->unsigned();
		    $table->enum('fabric_type', array('face', 'lining'));
		    $table->decimal('unit_price');
		    $table->timestamps();
		});
		
		Schema::table('order_fabrics', function(Blueprint $table) {
		    $table->dropColumn('fabric_type');
		    $table->dropColumn('unit_price');
		    $table->enum('type', array('face','lining','additional'))->default('additional');
		});
	}

}
