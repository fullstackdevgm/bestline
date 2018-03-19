<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function(Blueprint $table) {
			$table->increments('id');
			
			$table->text('notes');
			$table->text('ticket_notes');
			$table->text('invoice_notes');
			
			// A string put on the side of a shipping box to identify the order
			$table->string('sidemark');
			
			$table->date('date_received');
			$table->date('date_due');
			$table->date('date_shipped');
			
			$table->string('purchase_order');
			
			// The amount and check number of a deposit, if any
			$table->decimal('deposit_amount');
			$table->decimal('deposit_check_no');
			
			// The equation used to calculate a discount
			$table->decimal('discount_formula');
			// The equation used to increase price for a rush
			$table->decimal('rush_cost_formula');
			
			// The equation used for boxing price
			$table->decimal('boxing_cost_formula');
			
			// The customer type ID, which affects price
			$table->integer('customer_type_id')->unsigned();
			
			// The shipping type ID, which affects shipping costs
			$table->integer("shipping_method_id")->unsigned()->nullable();
			
			$table->integer('company_id')->unsigned();
			
			$table->foreign('customer_type_id')
					->references('id')
					->on('customer_types');
			
			$table->foreign('shipping_method_id')
					->references('id')
					->on('shipping_types');
			
			$table->foreign('company_id')
					->references('id')
					->on('companies');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('orders');
	}

}