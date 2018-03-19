<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class MakePricingTypeFormulaNullable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('pricing_types', function(Blueprint $table) {
		    $table->dropColumn('formula');
		});
		
	    Schema::table('pricing_types', function(Blueprint $table) {
	        $table->string('formula')->nullable();
	    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    Schema::table('pricing_types', function(Blueprint $table) {
	        $table->dropColumn('formula');
	    });
	    
        Schema::table('pricing_types', function(Blueprint $table) {
            $table->string('formula');
        });
	}

}