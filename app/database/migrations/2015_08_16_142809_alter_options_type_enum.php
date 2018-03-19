<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOptionsTypeEnum extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE `options` change `pricing_type` `pricing_type` enum('sqft','linear','flat','percent','yard','parent','calico', 'percentminus','tier','l1','l2','l3','w1','w2','w3','l1w1','l1w2', 'l2w1','l2w2') COLLATE utf8_unicode_ci DEFAULT NULL;");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}


