<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration 
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function(Blueprint $table) {
            $table->increments('id');
            $table->decimal('price', 6, 2)->nullable();
            $table->string('product_name', 255);
            $table->boolean('book')->nullable();
            $table->boolean('deco')->nullable();
            $table->boolean('poufy')->nullable();
            $table->string('pricing_type', 100)->nullable();
            $table->decimal('calico_price', 7, 3)->nullable();
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
            $table->decimal('price_plus_percentage', 6, 3)->nullable();
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
        Schema::drop('products');
    }

}