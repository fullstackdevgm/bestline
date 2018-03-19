<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFabricInventoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::table('fabrics', function(Blueprint $table) {
	        $table->string('sidemark')->nullable();
	        $table->text('notes')->nullable();
	        $table->string('image_url')->default('/images/default-fabric-img.png');
	        $table->integer('minimum_qty')->unsigned()->default(0);
	        $table->integer('owner_company_id')->unsigned()->nullable();
	        $table->dropColumn('com');
	        
	        $table->foreign('owner_company_id')
	              ->references('id')
	              ->on('companies')
	              ->onDelete('cascade');
	    });
	    
		Schema::create('fabrics_inventory', function(Blueprint $table) {
		    $table->increments('id');
		    $table->integer('fabric_id')->unsigned();
		    $table->integer('quantity')->unsigned();
		    $table->integer('adjustment')->default(0);
		    $table->string('reason');
		    $table->integer('by_user_id')->unsigned()->nullable();
		    
		    $table->timestamps();
		    
		    $table->foreign('fabric_id')
		          ->references('id')
		          ->on('fabrics')
		          ->onDelete('cascade');
		          
		    $table->foreign('by_user_id')
		          ->references('id')
		          ->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    Schema::table('fabrics', function(Blueprint $table) {
	        $table->dropColumn('sidemark');
	        $table->dropColumn('notes');
	        $table->dropColumn('image_url');
	        $table->dropColumn('minimum_qty');
	        $table->dropForeign('fabrics_owner_company_id_foreign');
	        $table->dropColumn('owner_company_id');
	        $table->boolean('com');
	        
	    });
	    
		Schema::drop('fabrics_inventory');
	}

}
