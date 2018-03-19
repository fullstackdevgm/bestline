<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValanceValuesToOrderLines extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_lines', function(Blueprint $table)
		{
			$table->boolean('has_shade')->default(TRUE)->after('updated_at');
			$table->dropColumn('valance_depth');
			$table->boolean('has_valance')->default(FALSE)->after('return');
			$table->decimal('valance_return', 8, 3)->nullable()->after('valance_height');
			$table->decimal('valance_headerboard', 8, 3)->nullable()->after('valance_return');
			$table->decimal('valance_price', 8, 2)->nullable()->after('shade_price');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_lines', function(Blueprint $table)
		{
			$table->dropColumn('valance_return');
			$table->decimal('valance_depth', 8, 3)->nullable();
			$table->dropColumn('valance_headerboard');
			$table->dropColumn('has_shade');
			$table->dropColumn('has_valance');
			$table->dropColumn('valance_price');
		});
	}

}
