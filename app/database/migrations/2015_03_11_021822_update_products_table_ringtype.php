<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProductsTableRingtype extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function(Blueprint $table) {
            $table->decimal('ring_from_edge', 6, 2);
            $table->enum('ring_type', ['brass', 'silver', 'white']);
        });
        
        Schema::table('finalized_order_lines', function(Blueprint $table) {
            $table->decimal('product_ring_from_edge', 6, 2);
            $table->enum('product_ring_type', ['brass', 'sliver', 'white']);
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
            $table->dropColumn('ring_from_edge');
            $table->dropColumn('ring_type');
        });
        
        Schema::table('finalized_order_lines', function(Blueprint $table) {
            $table->dropColumn('product_ring_from_edge');
            $table->dropColumn('product_ring_type');
        });
    }
    
}
