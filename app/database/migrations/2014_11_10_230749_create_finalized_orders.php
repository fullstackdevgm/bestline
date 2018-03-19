<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinalizedOrders extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('finalized_orders', function(Blueprint $table) {
		    
		    $table->increments('id');
		    
		    $table->string('notes');
		    $table->string('ticket_notes');
		    $table->string('invoice_notes');
		    $table->string('sidemark');
		    $table->date('date_received');
		    $table->date('date_due');
		    $table->date('date_shipped');
		    $table->string('purchase_order');
		    $table->decimal('deposit_amount', 8, 2);
		    
		    $table->string('ct_name');
		    $table->string('ct_description');
		    $table->string('ct_pricemod_equ')->nullable();
		    
		    $table->string('sm_name');
		    $table->string('sm_description');
		    $table->string('sm_formula');
		    
		    $table->string('company_website');
		    $table->string('company_notes');
		    $table->string('company_account_no');
		    $table->string('company_area');
		    $table->string('company_name');
		    $table->string('company_credit_terms');
		    $table->text('company_credit_term_notes');
		    
		    $table->string('sa_first_name');
		    $table->string('sa_last_name');
		    $table->string('sa_address1');
		    $table->string('sa_address2');
		    $table->string('sa_city');
		    $table->string('sa_state');
		    $table->string('sa_zip');
		    
		    $table->string('ba_first_name');
		    $table->string('ba_last_name');
		    $table->string('ba_address1');
		    $table->string('ba_address2');
		    $table->string('ba_city');
		    $table->string('ba_state');
		    $table->string('ba_zip');
		    $table->string('credit_terms');
		    $table->string('phone_number');
		    $table->string('fax_number');
		    $table->integer('deposit_check_no');
		    $table->integer('discount_percent');
		    $table->integer('rush_percent');
		    $table->decimal('boxing_cost', 8, 2);
		    $table->decimal('shipping_amount', 8, 2);
		    
		    $table->decimal('subtotal_calc', 8, 2);
		    $table->decimal('total_calc', 8, 2);
		    $table->decimal('discount_calc', 8, 2);
		    $table->decimal('rush_calc', 8, 2);
		    
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
		Schema::drop('finalized_orders');
	}

}
