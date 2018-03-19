<?php

use \Product\CutLengthFormula;

class ProductsTableSeeder extends Seeder {

    /**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
	    Eloquent::unguard();

	    DB::unprepared("SET foreign_key_checks=0");
	    Product::truncate();
	    DB::unprepared("SET foreign_key_checks=1");

        $this->call('RingTypeSeeder');

	    $csv = array_map('str_getcsv', file(__DIR__ . '/csv/shade_styles.csv'));

	    $header = array_flip(array_shift($csv));

	    foreach($csv as $line) {
	        if(count($line) != count($header)) {
	            continue;
	        }
	        $product = Product::create(array(
	            'name' => $line[$header['tShadeName']],
	            'pricing_type' => $this->convPricingType($line[$header['Pricing_Type']]),
	            'base_price' => $line[$header['nShadePrice']],
	            'ring_from_edge' => $line[$header['nOuterRings']],
	            'ring_type_id' => $this->convRingType($line[$header['tRingOrGrommet']]),
	            'ring_divisor' => $line[$header['nOuterRingsDivisor']],
	            'ring_minimum' => $line[$header['RingRowMinimum']],
                'shape' => ($line[$header['shadeShape']]!='') ? $line[$header['shadeShape']] : 'Square',
                'is_poufy' => ($line[$header['poufy']] === 'Yes') ? TRUE : FALSE,
                'poufy_panels_to_rod' => ($line[$header['PanelRodAdjustment']]!='') ? $line[$header['PanelRodAdjustment']] : NULL,
                'poufy_panels_to_pouf' => ($line[$header['PanelPoufAdjustment']]!='') ? $line[$header['PanelPoufAdjustment']] : NULL,
                'clutch_deduction' => ($line[$header['TDBU Clutch Deduction']]!='') ? $line[$header['TDBU Clutch Deduction']] : NULL,
                'cord_lock_deduction' => ($line[$header['TDBU Cord Lock Deduction']]!='') ? $line[$header['TDBU Cord Lock Deduction']] : NULL,
                'motorized_deduction' => ($line[$header['TDBU Motorized Deduction']]!='') ? $line[$header['TDBU Motorized Deduction']] : NULL,
                'rod_type' => $this->convRodType($line[$header['RodType']]),
                'cut_length_add' => ($line[$header['CutLengthAddInches_inv']]!='') ? $line[$header['CutLengthAddInches_inv']] : 0.000,
                'is_casual' => $this->isCasual($line[$header['tShadeName']]),
                'is_frontslat' => $this->isFrontslat($line[$header['tShadeName']]),
                'width_plus' => ($line[$header['nWidthPlus']]!='') ? $line[$header['nWidthPlus']] : NULL,
                'width_plus_lining' => ($line[$header['WidthPlusInterlining']]!='') ? $line[$header['WidthPlusInterlining']] : NULL,
                'length_plus' => ($line[$header['nLengthPlus']]!='') ? $line[$header['nLengthPlus']] : NULL,
                'width_times' => ($line[$header['nWidthMultiply']]!='') ? $line[$header['nWidthMultiply']] : NULL,
                'length_times' => ($line[$header['nLengthMultiply']]!='') ? $line[$header['nLengthMultiply']] : NULL,
                'product_cut_length_formula_id' => $this->convertCutLengthFormula($line[$header['CutLengthFormula']]),
	        ));

	        $product->save();
	    }
	}

    protected function convRingType($input) {
        $input = trim($input);

        switch($input) {
            case 'Silver Grommet':
                $name ='silver';
                break;
            case 'Brass Ring':
                $name = 'brass';
                break;
            case 'White Ring':
                $name = 'white';
                break;
            case '':
            case null:
                $name = 'none';
                break;
        }

        $ring = RingType::where('name', '=', $name)->first();

        if(!$ring instanceof RingType) {
            throw new \Exception("Unknown Ring Type: '$input'");
        }

        return $ring->id;
    }

    protected function convPricingType($input) {
        $input = trim($input);
        switch($input) {
            case 'Square Feet':
                return 'sqft';
            case 'Linear Foot':
                return 'linear';
            case 'Flat Fee':
                return 'flat';
            default:
                throw new \Exception("Unknown Pricing Type '$input'");
        }
    }

    protected function convRodType($input){
        $input = trim($input);
        switch($input) {
            case 'Flat Plastic':
                return 'flat_plastic';
            case 'Round Plastic':
                return 'round_plastic';
            case 'Wood':
                return 'wood';
            case '':
            case null:
                return null;
            default:
                throw new \Exception("Unknown Rod Type '$input'");
        }
    }

    protected function isCasual($name){

        $casualProductNames = array(
            'Casual',
            'Casual BU - NO Valance',
            'Casual BU - NO Valance-o',
            'Casual BU w/Valance',
            'Casual BU-o',
            'Casual TD/BU - NO Valance',
            'Casual TD/BU - NO Valance-o',
            'Casual TD/BU w/Valance',
            'Casual TD/BU-o',
            'Casual with Tails',
            'Casual with Tails Valance-o',
            'Casual with Tails-o',
            'Casual-o',
        );

        return in_array($name, $casualProductNames);
    }

    protected function isFrontslat($name){

        $frontslatProductNames = array(
            'Back Slat',
            'Back Slat BU',
            'Back Slat BU - NO VALANCE',
            'Back Slat TD/BU',
            'Back Slat TD/BU - NO VALANCE',
            'Front Slat',
            'Front Slat BU',
            'Front Slat BU - NO VALANCE',
            'Front Slat TD/BU',
            'Front Slat TD/BU - NO VALANCE',
            'Shirred Front Slat'
        );

        return in_array($name, $frontslatProductNames);
    }

    protected function convertCutLengthFormula($name){

        if(empty($name)) {
            return null;
        }

        $cutLengthFormula = CutLengthFormula::where('name', '=', $name)->firstOrFail();

        return $cutLengthFormula->id;
    }
}
