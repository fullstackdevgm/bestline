<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelatedOptionIdToPullTypes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('pull_types', function(Blueprint $table)
		{
			$table->integer('related_option_id')->unsigned()->nullable();
			$table->foreign('related_option_id')
				->references('id')
				->on('options')
				->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('pull_types', function(Blueprint $table)
		{
			$table->dropForeign('pull_types_related_option_id_foreign');
			$table->dropColumn('related_option_id');
		});
	}

}
