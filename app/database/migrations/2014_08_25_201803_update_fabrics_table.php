<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateFabricsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('fabrics', function(Blueprint $table) {
		    $table->dropColumn('pricing_type');
		});
		
	    Schema::table('fabrics', function(Blueprint $table) {
	        $table->enum('pricing_type', array('sqft', 'linear', 'flat', 'formula'))
	              ->default('sqft');
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
	    Schema::table('fabrics', function(Blueprint $table) {
	        $table->dropColumn('pricing_type');
	        $table->dropColumn('formula');
	    });
	    
		Schema::table('fabrics', function(Blueprint $table) {
		    $table->enum('pricing_type', array('sqft', 'linear', 'flat'));
		});
	}

}