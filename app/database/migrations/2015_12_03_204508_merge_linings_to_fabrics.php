<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MergeLiningsToFabrics extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		//adding column to allow for rollback
		Schema::table('fabrics', function(Blueprint $table){

			$table->boolean('imported_from_linings');	
		});

		//migrate all values from linings to fabrics
		DB::statement("INSERT INTO fabrics 
			(color, pattern, pricing_type, created_at, updated_at, imported_from_linings) 
			SELECT code as color, description as pattern, pricing_type, created_at, updated_at, 1 as imported_from_linings
			FROM linings
		");

		$liningType = DB::table('fabric_types')->where('type', '=' ,'lining')->lists('id');
		$importedFabrics = Fabric::where('imported_from_linings', '=', 1)->get();

		foreach($importedFabrics as $iFabric){
			$iFabric->types()->attach($liningType);
		}

		Schema::table('fabrics', function(Blueprint $table){

			$table->dropColumn('imported_from_linings');		
		});

		//drop legacy linings table
		Schema::drop('linings');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

		Schema::create('linings', function(Blueprint $table) {

			//you'll need to re-seed the linings table if you're not rolling this back all the way `php artisan db:seed --class=LiningsSeeder`
			//you may also need to delete rows that were added to the fabrics table

		    $table->increments('id');
		    $table->string('code');
		    $table->string('description');
		    $table->enum('pricing_type', array('sqft', 'linear', 'flat'));
		    $table->timestamps();
		});
	}
}
