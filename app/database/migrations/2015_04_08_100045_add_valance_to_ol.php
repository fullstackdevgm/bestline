<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValanceToOl extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_lines', function(Blueprint $table) {
		    $table->decimal('valance_width', 8,3);
		    $table->decimal('valance_height', 8, 3);
		    $table->decimal('valance_depth', 8, 3);
		});
		
		Schema::table('finalized_order_lines', function(Blueprint $table) {
		    $table->decimal('valance_width', 8, 3);
		    $table->decimal('valance_height', 8, 3);
		    $table->decimal('valance_depth', 8, 3);
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
		    $table->dropColumn('valance_width');
		    $table->dropColumn('valance_height');
		    $table->dropColumn('valance_depth');
		});
		
		Schema::table('finalized_order_lines', function(Blueprint $table) {
		    $table->dropColumn('valance_width');
		    $table->dropColumn('valance_height');
		    $table->dropColumn('valance_depth');
		});
	}

}
