<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyPrices extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_prices', function(Blueprint $table)
		{
			$table->increments('id');
			$table->decimal('price', 8, 2)->nullable();
			$table->integer('company_id')->unsigned()->nullable();
			$table->integer('product_id')->unsigned()->nullable();
			$table->integer('fabric_id')->unsigned()->nullable();
			$table->integer('option_id')->unsigned()->nullable();
			$table->timestamps();

			$table->foreign('company_id')
				->references('id')
				->on('companies')
				->onDelete('cascade');
			$table->foreign('product_id')
				->references('id')
				->on('products')
				->onDelete('cascade');
			$table->foreign('fabric_id')
				->references('id')
				->on('fabrics')
				->onDelete('cascade');
			$table->foreign('option_id')
				->references('id')
				->on('options')
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
		Schema::drop('company_prices');
	}

}
