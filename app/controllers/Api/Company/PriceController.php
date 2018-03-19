<?php

namespace Api\Company;

use \Response;
use \Validator;
use \Exception;
use \Input;
use \Log;
use \Company;
use \Company\Price as CompanyPrice;
use \Product;
use \Option;
use \Fabric;
use \PricingType;

class PriceController extends \Api\BaseController
{
    public function getFabricPrices($company_id, $fabric_id){

        $company = Company::find($company_id);
        $hasCompany = $company instanceof Company;
        $fabric = Fabric::find($fabric_id);
        $hasFabric = $company instanceof Fabric;
        $prices = [];

        if(!$hasCompany){
            $prices = $fabric->company_prices->load('company');
        }

        return Response::json($prices);
    }

    public function getProductPrices($company_id, $product_id){

        $company = Company::find($company_id);
        $hasCompany = $company instanceof Company;
        $product = Product::find($product_id);
        $hasProduct = $company instanceof Product;
        $prices = [];

        if(!$hasCompany){
            $prices = $product->company_prices->load('company');
        }

        return Response::json($prices);
    }

    public function getOptionPrices($company_id, $option_id){

        $company = Company::find($company_id);
        $hasCompany = $company instanceof Company;
        $option = Option::find($option_id);
        $hasOption = $company instanceof Option;
        $prices = [];

        if(!$hasCompany){
            $prices = $option->company_prices->load('company');
        }

        return Response::json($prices);
    }

    public function getSelectOptions(){

        try {
            $options = new \stdClass();
            $options->companies = Company::orderBy('name', 'asc')->lists('name', 'id');
            $options->products = Product::orderBy('name', 'asc')->lists('name', 'id');
            $options->sub_options = Option::allLeaves()->lists('name', 'id');
            $fabrics = Fabric::whereNull('owner_company_id')->get();
            foreach($fabrics as $fabric){
                $fabric->name = $fabric->getNameAttribute();
            }
            $options->fabrics = $fabrics->lists('name', 'id');
            $options->pricing_types = PricingType::getTypes();
        } catch(Exception $e) {
            return Response::json(array('error' => array('message' => $e->getMessage(), 'trace' => $e->getTrace())), 422);
        }

        return Response::json($options);
    }

    public function savePrice(){

        $inputs = Input::all();
        $inputHasCompanyId = isset($inputs['company_id']);
        $inputHasPrice = isset($inputs['price']) && $inputs['price'] > 0;

        if(!$inputHasCompanyId){
            return Response::json(array('error' => array('message' => 'You must select a company')), 422);
        }

        $company = Company::find($inputs['company_id']);
        $hasCompany = $company instanceof Company;
        if(!$hasCompany){
            return Response::json(array('error' => array('message' => 'Could not find the company')), 422);
        }

        try {

            $companyPrice = CompanyPrice::createCompanyPriceFromInputs($inputs);
            $companyPrice->save();

        } catch(Exception $e) {
            return Response::json(array('error' => array('message' => $e->getMessage(), 'trace' => $e->getTrace())), 422);
        }

        $companyPrice->company = $company->toArray();

        return Response::json($companyPrice);
    }

    public function deletePrices($company_id, $price_id){

        $companyPrice = CompanyPrice::find($price_id);
        if(!$companyPrice instanceof CompanyPrice){
            return Response::json(array('error' => array('message' => 'Could not find price #'. $price_id)), 422);
        }

        try{
            $companyPrice->delete();
        } catch(Exception $e) {
            return Response::json(array('error' => array('message' => $e->getMessage(), 'trace' => $e->getTrace())), 422);
        }

        return Response::json($price_id);
    }
}