<?php

class CompanyController extends Controller {

    //view all companies
    public function all(){
    	
        return View::make('company.all');
    }
}