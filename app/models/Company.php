<?php

use Company\Price as CompanyPrice;

class Company extends Eloquent
{
    const CREDIT_TYPE_COD = "cod";
    const CREDIT_TYPE_NET10 = "net10";

    protected $table = 'companies';

    static protected $_creditTerms = array(
        'cod' => "Cash On Delivery",
        'net10' => "Net 10 Days"
    );

    static public function getCreditTermsDesc($key)
    {
        $terms = static::getCreditTerms();

        if(isset($terms[$key])) {
            return $terms[$key];
        }
        return "Unknown";
    }

    static public function getCreditTerms()
    {
        return static::$_creditTerms;
    }

    public function customerType()
    {
        return $this->belongsTo('CustomerType');
    }

    public function contacts()
    {
        return $this->belongsToMany('Contact', 'company_contacts', 'company_id', 'contact_id');
    }

    public function addresses()
    {
        return $this->hasMany('Address');
    }

    public function fabrics(){
        return $this->hasMany('Fabric', 'owner_company_id');
    }

    public function getCompanyFabricPrice($fabric_id)
    {
        return CompanyPrice::where('fabric_id', '=', $fabric_id)->where('company_id', '=', $this->id)->first();
    }

    public function getCompanyProductPrice($product_id)
    {
        return CompanyPrice::where('product_id', '=', $product_id)->where('company_id', '=', $this->id)->first();
    }

    public function getCompanyOptionPrice($option_id)
    {
        return CompanyPrice::where('option_id', '=', $option_id)->where('company_id', '=', $this->id)->first();
    }

    public function primaryShippingAddress(){
      return $this->belongsTo('Address');
    }

    public function primaryBillingAddress(){
      return $this->belongsTo('Address');
    }

    public function getBillingAddressAttribute(){

        $primaryBillingAddress = $this->primaryBillingAddress();
        $hasPrimaryBillingAddress = $primaryBillingAddress instanceof Address;

        if($hasPrimaryBillingAddress){

            return $primaryBillingAddress;
        } else {

            return $this->getAnyAddress();
        }
    }

    public function getShippingAddressAttribute(){

        $primaryShippingAddress = $this->primaryShippingAddress();
        $hasPrimaryShippingAddress = $primaryShippingAddress instanceof Address;

        if($hasPrimaryShippingAddress){

            return $primaryShippingAddress;
        } else {

            return $this->getAnyAddress();
        }
    }

    private function getAnyAddress(){
        $firstAddress = $this->addresses()->first();
        $hasAddress = $firstAddress instanceof Address;

        if($hasAddress){

          return $firstAddress;
        } else {

          $newAddress = new Address;
          $newAddress->address1 = 'No Address Added';
          return $newAddress;;
        }
    }

    static public function getValidationRules()
    {
        $rules = array(
            'name' => 'required',
            'credit_terms' => 'required',
        );

        return $rules;
    }
}
