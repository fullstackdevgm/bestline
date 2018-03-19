<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddTimestamps extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_lines', function(Blueprint $table) {
		    $table->timestamps();
		});
		
		Schema::table('order_line_fabrics', function(Blueprint $table) {
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
		Schema::table('order_lines', function(Blueprint $table) {
		    
		    if (Schema::hasColumn('order_lines', 'created_at')){
		       $table->dropTimestamps();
		    }
		});
		
		Schema::table('order_line_fabrics', function(Blueprint $table) {
		    $table->dropTimestamps();
		});
	}

}