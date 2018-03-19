<?php

class Address extends Eloquent
{
    protected $table = "addresses";

    const TYPE_SHIPPING = 'shipping';
    const TYPE_BILLING = 'billing';

    static public function getTypes($full = false)
    {
        if($full) {
            return array(
                static::TYPE_BILLING => "Billing",
                static::TYPE_SHIPPING => "Shipping",
            );
        }

        return array(
            static::TYPE_BILLING,
            static::TYPE_SHIPPING,
        );
    }

    public function shippingMethod()
    {
        return $this->belongsTo('ShippingMethod');
    }

    public function shippingMethods(){
        return $this->shipping_methods = ShippingMethod::all()->toArray();
    }

    static public function getValidationRules()
    {
        return array();
    }

    public function companies()
    {
        return $this->belongsTo('Company', 'company_id', 'id');
    }

    public function primaryShippingForCompany()
    {
        return $this->hasOne('Company', 'primary_shipping_address_id');
    }

    public function primaryBillingForCompany()
    {
        return $this->hasOne('Company', 'primary_billing_address_id');
    }

    public function setStatesSelectOptions(){
        return $this->states_select_options = State::getStatesList();
    }
}
