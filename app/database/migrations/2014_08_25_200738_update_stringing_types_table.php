<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateStringingTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('stringing_types', function(Blueprint $table) {
		    $table->string('formula')->nullable();
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
	    Schema::table('stringing_types', function(Blueprint $table) {
	        $table->dropColumn('formula');
	        $table->dropTimestamps();
	    });
	}

}