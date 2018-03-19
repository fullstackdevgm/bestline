<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompanyProductModTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
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

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    Schema::drop('company_product_mods');
	}

}