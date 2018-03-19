<?php

namespace ProductsTableSeeder;

use \Product\CutLengthFormula;
use \Eloquent;
use \DB;
use \DatabaseSeeder;

class CutLengthFormulaSeeder extends DatabaseSeeder
{
	public function run()
	{
		Eloquent::unguard();

		DB::unprepared("SET foreign_key_checks=0");
		CutLengthFormula::truncate();
		DB::unprepared("SET foreign_key_checks=1");

		$formulas = $this->getFormulas();

		foreach($formulas as $formulaArray){

			$formula = CutLengthFormula::create($formulaArray);
			$formula->save();
		}
	}

	protected function getFormulas(){
	    
		$formulas = [];

		$formulas[] = [
			'name' => 'FH + Board',
		];
		$formulas[] = [
			'name' => 'L + Board',
		];
		$formulas[] = [
			'name' => 'L + Spaces x 2',
		];
		$formulas[] = [
			'name' => 'L + Spaces x 4',
		];
		$formulas[] = [
			'name' => 'L + Spaces x 6',
		];
		$formulas[] = [
			'name' => 'Spaces x PS',
		];

	    return $formulas;
	}
}