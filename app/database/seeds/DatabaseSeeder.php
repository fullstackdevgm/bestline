<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		$this->call('AlertSeeder');
		$this->call('StationSeeder');
		$this->call('UserSeeder');
		$this->call('ProductsTableSeeder\CutLengthFormulaSeeder');
		$this->call('ProductsTableSeeder');
		$this->call('ShippingMethodsSeeder');
		$this->call('CompanySeeder');
		$this->call('ContactSeeder');
		$this->call('StatesSeeder');
		$this->call('OptionsSeeder');
		$this->call('LookupsSeeder');
		$this->call('PricingTypeSeeder');
		$this->call('OptionsSeeder');
		$this->call('OptionSeeder\DataSeeder');
		$this->call('FabricSeeder');
		$this->call('FabricSeeder\InventorySeeder');
		$this->call('CompanySeeder\PriceSeeder');
		$this->call('OrderSeeder');
		$this->call('OrderSeeder\OptionSeeder');
		$this->call('OrderSeeder\FabricSeeder');
		$this->call('OrderSeeder\FabricSeeder\OptionSeeder');
		$this->call('OrderSeeder\FabricSeeder\OptionSeeder\DataSeeder');
		$this->call('OrderSeeder\OrderLineSeeder');
		$this->call('OrderSeeder\OrderLineSeeder\OptionSeeder');
		$this->call('OrderSeeder\OrderCalculate');
		$this->call('OrderSeeder\FinalizedOrderSeeder');
		$this->call('OrderSeeder\FinalizedOrderSeeder\OrderLineSeeder');
	}

}
