<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class DropUnusedProductColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('products', function(Blueprint $table) {
		    $table->dropColumn('book');
		    $table->dropColumn('deco');
		    $table->dropColumn('poufy');
		    $table->dropColumn('lining');
		    $table->dropColumn('return');
		    $table->dropColumn('valance_cost');
		    $table->dropColumn('valance_minimum');
		    $table->dropColumn('rod_type');
		    $table->dropColumn('product_type');
		    $table->dropColumn('shade_shape');
		    $table->dropColumn('hoist_guide_type');
		    $table->dropColumn('side_hem');
		    $table->dropColumn('default_panel_spacing');
		    $table->dropColumn('outer_rings');
		    $table->dropColumn('outer_rings_divisor');
		    $table->dropColumn('ring_row_minimum');
		    $table->dropColumn('length_multiply');
		    $table->dropColumn('length_multiply_inv');
		    $table->dropColumn('length_plus');
		    $table->dropColumn('length_plus_inv');
		    $table->dropColumn('pouf_adjustment_inv');
		    $table->dropColumn('width_multiply');
		    $table->dropColumn('width_multiply_inv');
		    $table->dropColumn('width_plus');
		    $table->dropColumn('width_plus_inv');
		    $table->dropColumn('cut_length_add_inches');
		    $table->dropColumn('cut_length_add_inches_inv');
		    $table->dropColumn('cut_length_formula');
		    $table->dropColumn('height_pouf_adjustment');
		    $table->dropColumn('height_rod_adjustment');
		    $table->dropColumn('headerboard_in_deduction');
		    $table->dropColumn('headerboard_out_deduction');
		    $table->dropColumn('rod_deduction');
		    $table->dropColumn('tdbu_clutch_deduction');
		    $table->dropColumn('tdbu_cord_lock_deduction');
		    $table->dropColumn('tdbu_motorized_deduction');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('products', function(Blueprint $table) {
		    $table->boolean('book')->nullable();
		    $table->boolean('deco')->nullable();
		    $table->boolean('poufy')->nullable();
		    $table->boolean('lining')->nullable();
		    $table->boolean('return')->nullable();
		    $table->decimal('valance_cost', 6, 2)->nullable();
		    $table->decimal('valance_minimum', 6, 2)->nullable();
		    $table->string('rod_type', 100)->nullable();
		    $table->string('product_type', 100)->nullable();
		    $table->string('shade_shape', 100)->nullable();
		    $table->string('hoist_guide_type', 100)->default("ring");
		    $table->decimal('side_hem', 6, 3)->nullable();
		    $table->decimal('default_panel_spacing', 6, 2)->nullable();
		    $table->decimal('outer_rings', 6, 3)->nullable();
		    $table->decimal('outer_rings_divisor', 6, 3)->nullable();
		    $table->integer('ring_row_minimum')->nullable();
		    $table->decimal('length_multiply', 6, 3)->nullable();
		    $table->decimal('length_multiply_inv', 6, 3)->nullable();
		    $table->decimal('length_plus', 6, 3)->nullable();
		    $table->decimal('length_plus_inv', 6, 3)->nullable();
		    $table->decimal('pouf_adjustment_inv', 6, 3)->nullable();
		    $table->decimal('width_multiply', 6, 3)->nullable();
		    $table->decimal('width_multiply_inv', 6, 3)->nullable();
		    $table->decimal('width_plus', 6, 3)->nullable();
		    $table->decimal('width_plus_inv', 6, 3)->nullable();
		    $table->decimal('cut_length_add_inches', 7, 3)->nullable();
		    $table->decimal('cut_length_add_inches_inv', 7, 3)->nullable();
		    $table->decimal('cut_length_formula', 7, 3)->nullable();
		    $table->decimal('height_pouf_adjustment', 6, 3)->nullable();
		    $table->decimal('height_rod_adjustment', 6, 3)->nullable();
		    $table->decimal('headerboard_in_deduction', 6, 3)->nullable();
		    $table->decimal('headerboard_out_deduction', 6, 3)->nullable();
		    $table->decimal('rod_deduction', 6, 3)->nullable();
		    $table->decimal('tdbu_clutch_deduction', 6, 3)->nullable();
		    $table->decimal('tdbu_cord_lock_deduction', 6, 3)->nullable();
		    $table->decimal('tdbu_motorized_deduction', 6, 3)->nullable();
		});
	}

}