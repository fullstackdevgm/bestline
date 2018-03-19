<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllowNullableFabrics extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('fabrics', function(Blueprint $table)
		{
			DB::statement("
				ALTER TABLE `fabrics`
					ALTER `width` DROP DEFAULT,
					ALTER `repeat` DROP DEFAULT,
					ALTER `margin` DROP DEFAULT,
					ALTER `pattern` DROP DEFAULT,
					ALTER `color` DROP DEFAULT,
					ALTER `pricing_type` DROP DEFAULT;
			");
			DB::statement("
				ALTER TABLE `fabrics`
					CHANGE COLUMN `width` `width` DECIMAL(8,3) NULL,
					CHANGE COLUMN `repeat` `repeat` DECIMAL(8,3) NULL,
					CHANGE COLUMN `margin` `margin` DECIMAL(8,3) NULL,
					CHANGE COLUMN `pattern` `pattern` VARCHAR(255) NULL,
					CHANGE COLUMN `color` `color` VARCHAR(255) NULL,
					CHANGE COLUMN `pricing_type` `pricing_type` ENUM('sqft','linear','flat','l2w1','w1','l2','l1','yard','l2w2','formula') NULL;
					
			");

			$table->dropColumn('image_url');
			$table->string('image')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('fabrics', function(Blueprint $table)
		{
			//once you go null, trying to revert could cause migration errors because some values may already be null
			
			//can't return width, repeat, and margin to their previous states without first altering the date to make sure no issues will turn up. This doesn't seem like a priority right now.

			$table->dropColumn('image');
			$table->string('image_url')->default('/images/default-fabric-img.png');
		});
	}

}
