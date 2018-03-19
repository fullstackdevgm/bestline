<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionsDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('option_data', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('option_id')->unsigned()->nullable()->unique();
			$table->foreign('option_id')
				->references('id')
				->on('options')
				->onDelete('cascade');

			$table->integer('order_option_id')->unsigned()->nullable()->unique();
			$table->foreign('order_option_id')
				->references('id')
				->on('order_default_options')
				->onDelete('cascade');

			$table->integer('order_fabric_option_id')->unsigned()->nullable()->unique();
			$table->foreign('order_fabric_option_id')
				->references('id')
				->on('order_fabric_options')
				->onDelete('cascade');

			$table->integer('order_line_option_id')->unsigned()->nullable()->unique();
			$table->foreign('order_line_option_id')
				->references('id')
				->on('order_line_options')
				->onDelete('cascade');

			$table->decimal('size', 8, 3)->nullable();
			$table->decimal('inset_size_sides', 8, 3)->nullable();
			$table->decimal('inset_size_bottom', 8, 3)->nullable();
			$table->decimal('inset_size_top', 8, 3)->nullable();

			$table->integer('parent_option_id')->unsigned()->nullable();
			$table->foreign('parent_option_id')
				->references('id')
				->on('options')
				->onDelete('cascade');

			$table->integer('parent_order_option_id')->unsigned()->nullable();
			$table->foreign('parent_order_option_id')
				->references('id')
				->on('order_default_options')
				->onDelete('cascade');

			$table->integer('parent_order_fabric_option_id')->unsigned()->nullable();
			$table->foreign('parent_order_fabric_option_id')
				->references('id')
				->on('order_fabric_options')
				->onDelete('cascade');

			$table->integer('parent_order_line_option_id')->unsigned()->nullable();
			$table->foreign('parent_order_line_option_id')
				->references('id')
				->on('order_line_options')
				->onDelete('cascade');

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
		Schema::drop('option_data');
	}

}
