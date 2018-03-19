<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFabricPricingTypes extends Migration {

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
	        $table->enum('pricing_type', ['sqft', 'linear', 'flat', 'l2w1', 'w1', 'l2', 'l1', 'yard', 'l2w2', 'formula']);
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
		});
		
	    Schema::table('fabrics', function(Blueprint $table) {
	        $table->enum('pricing_type', ['sqft','linear','flat','formula']);
	    });
	}

}
