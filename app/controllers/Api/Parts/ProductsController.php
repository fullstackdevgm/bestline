<?php

namespace Api\Parts;

use \Log;
use \Response;
use \Product;
use \Input;
use \Product\CutLengthFormula;
use \RingType;
use \PricingType;
use \Exception;

class ProductsController extends \Api\BaseController
{

    public function all(){

        $products = Product::orderBy('name', 'asc')->get();

      	return Response::json($products);
    }

    public function selectOptions(){

        try {
            $options = new \stdClass();
            $options->ring_types = RingType::orderBy('name', 'asc')->get(['id', 'description'])->toArray();
            $options->pricing_types = PricingType::getTypes();
            $options->rod_types = Product::getRodTypesForAdmin();
            $options->cut_length_formulas = CutLengthFormula::orderBy('name', 'asc')->get(['id', 'name'])->toArray();
            $options->shapes = ['Austrian','Square/Trapezoid','Square','Dog Ear','Balloon','SCloud','PCloud','TDBU/BU']; //I hate that this is like this in the system, but alas #260
        } catch(Exception $e) {
            return Response::json(array('error' => array('message' => $e->getMessage(), 'trace' => $e->getTrace())), 422);
        }

        return Response::json($options);
    }

    public function save($product_id){

    	$inputs = Input::all();

    	try {

    	    $product = Product::createProductFromInputs($inputs);
    	    $product->save();

    	} catch(Exception $e) {
    	    return Response::json(array('error' => array('message' => $e->getMessage(), 'trace' => $e->getTrace())), 422);
    	}

    	return Response::json($product);
    }

    public function delete($product_id){

        $product = Product::find($product_id);
        if(!$product instanceof Product){
            return Response::json(array('error' => array('message' => 'Could not find product #'. $product_id)), 422);
        }

        try{
            $product->delete();
        } catch(Exception $e) {
            return Response::json(array('error' => array('message' => $e->getMessage(), 'trace' => $e->getTrace())), 422);
        }

        return Response::json($product_id);
    }

    public function getProductById($product_id){
        $product = Product::find($product_id);

        if(!$product instanceof Product) {
            return Response::json(array('error' => array('message' => 'Could not find product #'. $product_id)), 422);
        }

        return Response::json($product);
    }
}
