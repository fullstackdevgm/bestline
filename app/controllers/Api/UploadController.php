<?php

namespace Api;

use \Response;
use \Input;
use \AUTH;

class UploadController extends BaseController
{
    function upload($type){

	    try {

	    	$user = AUTH::user();
	    	$isBase64 = false;

	    	if($type === 'fabric'){

	    		$destinationPath = public_path() . '/uploads/fabrics/';

	    		if(Input::hasFile('image')){

	    			$file = Input::file('image');
	    			$originalFileName = $file->getClientOriginalName();
	    		} else if(Input::has('base64')) {

	    			$isBase64 = true;
	    			$base64Input = Input::get('base64');
	    			$decodedBase64Image = $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Input));
	    			$originalFileName = 'webcam-' . time () . '.jpg';
	    		} else {
	    			return Response::json(array('error' => array('message' => 'You must upload a file.')), 422);
	    		}

	    		$filename = 'user-' . $user->id . "-file-" . $originalFileName;
	    	} else {
	    		return Response::json(array('error' => array('message' => 'We don\'t handle this type of upload yet.')), 422);
	    	}

	    	if(!$isBase64){

	    		$file->move($destinationPath, $filename);
	    	} else {

	    		$numberOfBytesWritten = file_put_contents($destinationPath . $filename, $decodedBase64Image);
	    		if(!$numberOfBytesWritten){
	    			return Response::json(array('error' => array('message' => 'The file couldn\'t be parsed.')), 422);
	    		}
	    	}

	    } catch(Exception $e) {
	        return Response::json(array('error' => array('message' => $e->getMessage(), 'trace' => $e->getTrace())), 422);
	    }

	    return Response::json($filename);
    }
}