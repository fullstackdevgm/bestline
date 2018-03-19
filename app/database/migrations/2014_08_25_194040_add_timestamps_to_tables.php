<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddTimestampsToTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('company_product_mods', function(Blueprint $table) {
		    $table->timestamps();
		});
		
	    Schema::table('order_fabrics', function(Blueprint $table) {
	        $table->timestamps();
	    });
	    
        Schema::table('pricing_types', function(Blueprint $table) {
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
		Schema::table('company_product_mods', function(Blueprint $table) {
		    $table->dropTimestamps();
		});
		
	    Schema::table('order_fabrics', function(Blueprint $table) {
	        $table->dropTimestamps();
	    });
	    
        Schema::table('pricing_types', function(Blueprint $table) {
            $table->dropTimestamps();
        });
	}

}