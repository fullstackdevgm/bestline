<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductRingType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('products', function(Blueprint $table) {
		    if(Schema::hasColumn('products', 'ring_type')) {
		        $table->dropColumn('ring_type');
		    }
		    
		    $table->integer('ring_type_id')->unsigned();
		    
		    DB::unprepared("SET foreign_key_checks=0");
		    
		    $table->foreign('ring_type_id')
		          ->references('id')
		          ->on('ring_types');
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
			$table->dropForeign('products_ring_type_id_foreign');
		    $table->dropColumn('ring_type_id');
		    $table->enum('ring_type', ['brass', 'silver', 'white']);
		});
	}

}
