<?php

use \Carbon\Carbon;

class Fabric extends Eloquent
{
    protected $table = "fabrics";

    protected static $grades = [
        'A' => array(
            'name' => 'A'
        ),
        'F' => array(
            'name' => 'F'
        ),
        'S' => array(
            'name' => 'S'
        ),
        'C' => array(
            'name' => 'C'
        ),
        'E' => array(
            'name' => 'E'
        ),
        'D' => array(
            'name' => 'D'
        ),
        'R' => array(
            'name' => 'R'
        ),
        'B' => array(
            'name' => 'B'
        ),
        'T' => array(
            'name' => 'T'
        ),
    ];

    public static function gradeOptions(){
        $gradeOptions = [];

        foreach(static::$grades as $key => $type){
            $gradeOptions[$key] = $type['name'];
        }

        return $gradeOptions;
    }

    public function types()
    {
        return $this->belongsToMany('Fabric\Type', 'selected_fabric_types', 'fabric_id', 'type_id');
    }

    public function getPricingType()
    {
        return PricingType::where('type', '=', $this->pricing_type)->first();
    }

    public function ownerCompany()
    {
        return $this->belongsTo('Company', 'owner_company_id');
    }

    public function inventory()
    {
        return $this->hasOne('Inventory\Fabric', 'fabric_id');
    }

    public function allInventory(){
        return $this->hasMany('Inventory\Fabric', 'fabric_id');
    }

    public function relatedOption(){
        return $this->belongsTo('Option', 'related_option_id');
    }

    public function companyPrices(){
        return $this->hasMany('\Company\Price');
    }

    public function getPrettyQuantityAttribute()
    {
        $quantity = $this->quantity;

        if($quantity >= $this->minimum_qty) {
            return $quantity;
        }

        return "<font color='red'>$quantity</font>";
    }

    public function getFlawsAttribute(){
        return explode(',', $this->flaws_string);
    }

    public function getQuantityAttribute()
    {
        if(!$this->inventory) {
            return 0;
        }

        return $this->inventory->quantity;
    }

    public function getNameAttribute()
    {
        $name = "";
        $separator = " - ";
        $separatorLen = strlen($separator);
        if($this->sidemark){
            $name = $name . $this->sidemark . $separator;
        }
        if($this->pattern){
            $name = $name . $this->pattern . $separator;
        }
        if($this->color){
            $name = $name . $this->color . $separator;
        }
        if($this->ticket_number){
            $name = $name . $this->ticket_number . $separator;
        }
        if(empty($name)){
            $name = 'Unknown';
        } else {
            $name = substr($name, 0, -$separatorLen);
        }
        return $name;
    }

    public static function createFabricFromInputs($inputs){

        //find fabric or create one
        if(isset($inputs['id'])){
            $fabric = Fabric::find($inputs['id']);

            if(!$fabric instanceof Fabric) {
                $fabric = new Fabric;
            }
        } else {
            $fabric = new Fabric;
        }

        //set fabric values
        $fabric->sidemark = isset($inputs['sidemark']) ? $inputs['sidemark'] : NULL;
        $fabric->owner_company_id = isset($inputs['owner_company_id']) ? $inputs['owner_company_id'] : NULL;
        $fabric->width = isset($inputs['width']) ? $inputs['width'] : 0;
        $fabric->repeat = isset($inputs['repeat']) ? $inputs['repeat'] : 0;
        $fabric->minimum_qty = isset($inputs['minimum_qty']) ? $inputs['minimum_qty'] : 0;
        $fabric->pattern = isset($inputs['pattern']) ? $inputs['pattern'] : NULL;
        $fabric->color = isset($inputs['color']) ? $inputs['color'] : NULL;
        $fabric->notes = isset($inputs['notes']) ? $inputs['notes'] : NULL;
        $fabric->grade = isset($inputs['grade']) ? $inputs['grade'] : NULL;
        $fabric->related_option_id = isset($inputs['related_option_id']) ? $inputs['related_option_id'] : NULL;
        $fabric->date_received = isset($inputs['date_received']) ?  Carbon::createFromFormat('Y-m-d', $inputs['date_received']) : NULL;
        $fabric->ticket_number = isset($inputs['ticket_number']) ? $inputs['ticket_number'] : NULL;
        $fabric->flaws_string = isset($inputs['flaws_string']) ? $inputs['flaws_string'] : NULL;
        $fabric->image = isset($inputs['image']) ? $inputs['image'] : NULL;

        //determine pricing
        $pricing_type = isset($inputs['pricing_type']) ? $inputs['pricing_type'] : NULL;
        if(!$pricing_type) {
            $pricing_type = 'yard';
            $unit_price = 0;
        } else {
            $unit_price = isset($inputs['unit_price']) ? $inputs['unit_price'] : 0;
        }
        $fabric->unit_price = $unit_price;
        $fabric->pricing_type = $pricing_type;

        return $fabric;
    }
}