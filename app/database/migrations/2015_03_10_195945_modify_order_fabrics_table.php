<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyOrderFabricsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_fabrics', function(Blueprint $table) {
		    $table->dropColumn('fabric_type');
		    $table->integer('fabric_type_id')->unsigned();
		    
		    $table->foreign('fabric_type_id')
		          ->references('id')
		          ->on('fabric_types')
		          ->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_fabrics', function(Blueprint $table) {
			$table->dropForeign('order_fabrics_fabric_type_id_foreign');
		    $table->dropColumn('fabric_type_id');
		    $table->enum('fabric_type', ['face', 'lining']);
		});
	}

}
