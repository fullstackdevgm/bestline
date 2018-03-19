<?php

namespace Api\Parts;

use \Log;
use \Response;
use \Option;
use \Input;
use \PricingType;
use \Exception;

class OptionsController extends \Api\BaseController
{

    public function all(){

        $options = Option::roots()->orderBy('name', 'asc')->get();

        foreach($options as $option){
            $option->sub_options = $option->descendants()->get()->toArray();
        }

      	return Response::json($options);
    }

    public function allSuboptions(){

        $suboptions = Option::with('parents')->whereNotNull('parent_id')->orderBy('name', 'asc')->get();

        return Response::json($suboptions);
    }

    public function selectOptions(){

        try {
            $selectOptions = new \stdClass();
            $selectOptions->parent_options = Option::roots()->get(['id', 'name'])->toArray();
            $selectOptions->pricing_types = PricingType::getTypes();
        } catch(Exception $e) {
            return Response::json(array('error' => array('message' => $e->getMessage(), 'trace' => $e->getTrace())), 422);
        }

        return Response::json($selectOptions);
    }

    public function save($option_id){

    	$inputs = Input::all();

    	try {

    	    $option = Option::createOptionFromInputs($inputs);
    	    $option->save();

    	} catch(Exception $e) {
    	    return Response::json(array('error' => array('message' => $e->getMessage(), 'trace' => $e->getTrace())), 422);
    	}

    	return Response::json($option);
    }

    public function delete($option_id){

        $option = Option::find($option_id);
        if(!$option instanceof Option){
            return Response::json(array('error' => array('message' => 'Could not find option #'. $option_id)), 422);
        }

        try{
            $option->delete();
        } catch(Exception $e) {
            return Response::json(array('error' => array('message' => $e->getMessage(), 'trace' => $e->getTrace())), 422);
        }

        return Response::json($option_id);
    }

    public function getOptionById($option_id){
        $option = Option::find($option_id);

        if(!$option instanceof Option) {
            return Response::json(array('error' => array('message' => 'Could not find option #'. $option_id)), 422);
        }

        $option->sub_options = $option->descendants()->get()->toArray();
        $option->load('parents');

        return Response::json($option);
    }
}
