<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFabricsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fabrics', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->decimal('width')->nullable(); // Fabric width in inches
			$table->integer('repeat')->nullable();
			$table->decimal('margin')->nullable();
			$table->enum('pricing_type', array('sqft', 'linear', 'flat'));
			$table->decimal('price');
			$table->boolean('com');
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
		Schema::drop('fabrics');
	}

}