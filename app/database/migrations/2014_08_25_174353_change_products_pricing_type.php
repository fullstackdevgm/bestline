<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ChangeProductsPricingType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('products', function(Blueprint $table) {
		    $table->dropColumn('pricing_type');
		});
		
	    Schema::table('products', function(Blueprint $table) {
	        $table->enum('pricing_type', array('sqft', 'linear', 'flat'));
	    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    Schema::table('products', function(Blueprint $table) {
	        $table->dropColumn('pricing_type');
	    });
    
        Schema::table('products', function(Blueprint $table) {
            $table->string('pricing_type');
        });
	}

}