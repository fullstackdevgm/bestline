<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPricingTypes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('pricing_types', function(Blueprint $table) {
		    $table->dropColumn('type');
		});
		
		Schema::table('pricing_types', function(Blueprint $table) {
		    $table->enum('type', array('sqft','linear','flat','l2w1','w1','l2','l1','percent','yard','l2w2'));
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
		    $table->dropColumn('type');
		});
		
		Schema::table('pricing_types', function(Blueprint $table) {
		    $table->enum('type', array('sqft','linear','flat'));
		});
	}

}
