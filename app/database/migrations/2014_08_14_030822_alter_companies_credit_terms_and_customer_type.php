<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterCompaniesCreditTermsAndCustomerType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('companies', function(Blueprint $table) {
			$table->dropColumn('customer_type');
			$table->dropColumn('credit_terms');
		});
		
		Schema::table('companies', function(Blueprint $table) {
			$table->integer('customer_type_id')->unsigned();
			$table->enum('credit_terms', array_keys(Company::getCreditTerms()));
			$table->text('credit_term_notes');
				
			$table->foreign('customer_type_id')
					->references('id')
					->on('customer_types');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('companies', function(Blueprint $table) {
			$table->dropForeign('companies_customer_type_id_foreign');
			$table->dropColumn('customer_type_id');
			$table->dropColumn('credit_terms');
			$table->dropColumn('credit_term_notes');
			
			if (!Schema::hasColumn('companies', 'credit_terms')){
			    $table->string('credit_terms');
			}
			$table->string('customer_type');
		});
	}

}