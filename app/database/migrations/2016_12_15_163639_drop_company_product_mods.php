<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropCompanyProductMods extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('company_product_mods');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::create('company_product_mods', function(Blueprint $table) {
		    $table->increments('id');
		    $table->integer('company_id')->unsigned();
		    $table->integer('product_id')->unsigned();
		    $table->decimal('price', 6, 2)->nullable();

		    $table->foreign('company_id')
		           ->references('id')
		           ->on('companies')
		           ->onDelete('cascade');

		    $table->foreign('product_id')
		           ->references('id')
		           ->on('products')
		           ->onDelete('cascade');
		});
	}

}
