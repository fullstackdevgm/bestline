<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationshipsToOrderLineOptions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_line_options', function(Blueprint $table)
		{
		    $table->integer('order_option_id')->unsigned()->nullable();
		    $table->integer('order_fabric_option_id')->unsigned()->nullable();
            
            $table->foreign('order_option_id')
                  ->references('id')
                  ->on('order_default_options')
                  ->onDelete('set null');

            $table->foreign('order_fabric_option_id')
                  ->references('id')
                  ->on('order_fabric_options')
                  ->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_line_options', function(Blueprint $table)
		{
			$table->dropForeign('order_line_options_order_option_id_foreign');
			$table->dropColumn('order_option_id');
			$table->dropForeign('order_line_options_order_fabric_option_id_foreign');
			$table->dropColumn('order_fabric_option_id');
		});
	}

}
