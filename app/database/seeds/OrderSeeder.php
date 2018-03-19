<?php

use Carbon\Carbon;
class OrderSeeder extends DatabaseSeeder
{
    public function run()
    {
        $this->call('ShippingMethodsSeeder');

        DB::unprepared("SET foreign_key_checks=0");
        DB::table('orders')->truncate();
        DB::unprepared("SET foreign_key_checks=1");

        $orders = $this->getOrders();

        foreach($orders as $order){
            $orderObj = Order::create($order);
            $orderObj->date_received = Carbon::now()->addDay();
            $orderObj->date_due = Carbon::now()->addMonth();
            $orderObj->save();
        }
    }

    public function getOrders(){
        
        $orders = [];

        $products = new stdClass();
        $products->BackSlatTDBU = Product::where('name', '=', 'Back Slat TD/BU')->firstOrFail();
        $products->BackSlat = Product::where('name', '=', 'Back Slat')->firstOrFail();
        $products->SoftCasual = Product::where('name', '=', 'Soft Casual')->firstOrFail();
        $products->Casual = Product::where('name', '=', 'Casual')->firstOrFail();
        $products->DogEar = Product::where('name', '=', 'Dog Ear')->firstOrFail();

        //Lam's Orders
        $company = Company::where('name', '=', 'Lam\'s Custom Draperies')->firstOrFail();
        $orders[] = array(
            'notes' => 'Only Shades 1.5" White Banding Inset 2" from sides and bottom. Return extra fabric #6581120,6581232/F360", B78"',
            'ticket_notes' => 'Only Shades 1.5" White Banding Inset 2" from sides and bottom. Return extra fabric #6581120,6581232/F360", B78"',
            'invoice_notes' => 'Only Shades 1.5" White Banding Inset 2" from sides and bottom. Return extra fabric #6581120,6581232/F360", B78"',
            'sidemark' => 'Ratna/Girls',
            'purchase_order' => '1234',
            'deposit_amount' => 0.00,
            'company_id' => $company->id,
            'shipping_address_id' => $company->addresses()->first()->id,
            'billing_address_id' => $company->addresses()->first()->id,
            'shipping_method_id' => $company->addresses()->first()->shipping_method->id,
            'credit_terms' => $company->credit_terms,
            'deposit_check_no' => '1024',
            'discount_percent' => 0,
            'rush_percent' => 0,
            'boxing_cost' => 0,
            'product_id' => $products->BackSlatTDBU->id,
            'ring_type_id' => $products->BackSlatTDBU->ring_type_id,
            'contact_id' => $company->contacts()->first()->id,
        );
        $orders[] = array(
            'ticket_notes' => 'Return Extra Fabric. #6581272/216"',
            'sidemark' => 'Alice/Tracy Rm 1',
            'company_id' => $company->id,
            'shipping_address_id' => $company->addresses()->first()->id,
            'billing_address_id' => $company->addresses()->first()->id,
            'shipping_method_id' => $company->addresses()->first()->shipping_method->id,
            'credit_terms' => $company->credit_terms,
            'deposit_check_no' => 0,
            'discount_percent' => 0,
            'rush_percent' => 0,
            'boxing_cost' => 0,
            'product_id' => $products->BackSlat->id,
            'ring_type_id' => $products->BackSlat->ring_type_id,
            'contact_id' => $company->contacts()->first()->id,
        );

        //add calico orders
        $company = Company::where('name', '=', 'Calico Corners - Denver')->firstOrFail();
        $orders[] = array(
            'sidemark' => 'Noolas/SCAS',
            'purchase_order' => '489669',
            'deposit_amount' => 0.00,
            'company_id' => $company->id,
            'shipping_address_id' => $company->addresses()->first()->id,
            'billing_address_id' => $company->addresses()->first()->id,
            'shipping_method_id' => $company->addresses()->first()->shipping_method->id,
            'credit_terms' => 'net10',
            'deposit_check_no' => 0,
            'discount_percent' => 0,
            'rush_percent' => 0,
            'boxing_cost' => 0,
            'product_id' => $products->SoftCasual->id,
            'ring_type_id' => $products->SoftCasual->ring_type_id,
            'contact_id' => 2,
        );

        //add The Roman Shade Company orders
        $company = Company::where('name', '=', 'The Roman Shade Company')->firstOrFail();
        $orders[] = array(
            'sidemark' => 'Punzi/Klausner/Olivia',
            'purchase_order' => '12033',
            'deposit_amount' => 0.00,
            'company_id' => $company->id,
            'shipping_address_id' => $company->shipping_address->id,
            'billing_address_id' => $company->billing_address->id,
            'shipping_method_id' => $company->addresses()->first()->shipping_method->id,
            'credit_terms' => 'net10',
            'deposit_check_no' => 0,
            'discount_percent' => 0,
            'rush_percent' => 0,
            'boxing_cost' => 0,
            'product_id' => $products->Casual->id,
            'ring_type_id' => $products->Casual->ring_type_id,
            'contact_id' => $company->contacts()->first()->id,
        );
        $orders[] = array(
            'sidemark' => 'Corcoran/Cotter/Nook',
            'purchase_order' => '12033',
            'deposit_amount' => 0.00,
            'company_id' => $company->id,
            'shipping_address_id' => $company->shipping_address->id,
            'billing_address_id' => $company->billing_address->id,
            'shipping_method_id' => $company->addresses()->first()->shipping_method->id,
            'credit_terms' => 'net10',
            'deposit_check_no' => 0,
            'discount_percent' => 0,
            'rush_percent' => 0,
            'boxing_cost' => 0,
            'product_id' => $products->Casual->id,
            'ring_type_id' => $products->Casual->ring_type_id,
            'contact_id' => $company->contacts()->first()->id,
        );
        $orders[] = array(
            'notes' => '3/8" Banding along sides and bottom.',
            'ticket_notes' => '3/8" Banding along sides and bottom.',
            'invoice_notes' => '3/8" Banding along sides and bottom.',
            'sidemark' => 'Corcoran/Cotter/Mom\'s Bath',
            'purchase_order' => '12004.12R',
            'deposit_amount' => 0.00,
            'company_id' => $company->id,
            'shipping_address_id' => $company->shipping_address->id,
            'billing_address_id' => $company->billing_address->id,
            'shipping_method_id' => $company->addresses()->first()->shipping_method->id,
            'credit_terms' => 'net10',
            'deposit_check_no' => 0,
            'discount_percent' => 0,
            'rush_percent' => 0,
            'boxing_cost' => 0,
            'product_id' => $products->Casual->id,
            'ring_type_id' => $products->Casual->ring_type_id,
            'contact_id' => $company->contacts()->first()->id,
        );

        //add Pacific View Window Coverings orders
        $company = Company::where('name', '=', 'Pacific View Window Coverings')->firstOrFail();
        $orders[] = array(
            'notes' => 'TDBU w/6" Valance',
            'ticket_notes' => 'TDBU w/6" Valance',
            'invoice_notes' => 'TDBU w/6" Valance',
            'sidemark' => 'Foster/K',
            'purchase_order' => '123',
            'deposit_amount' => 0.00,
            'company_id' => $company->id,
            'shipping_address_id' => $company->addresses()->first()->id,
            'billing_address_id' => $company->addresses()->first()->id,
            'shipping_method_id' => $company->addresses()->first()->shipping_method->id,
            'credit_terms' => $company->credit_terms,
            'deposit_check_no' => 0,
            'discount_percent' => 0,
            'rush_percent' => 0,
            'boxing_cost' => 0,
            'product_id' => $products->BackSlatTDBU->id,
            'ring_type_id' => $products->BackSlatTDBU->ring_type_id,
            'contact_id' => $company->contacts()->first()->id,
        );

        //add Peggy Klock Sewing orders
        $company = Company::where('name', '=', 'Peggy Klock Sewing')->firstOrFail();
        $orders[] = array(
            'notes' => 'Pick Up 4/4, 2" Banding Inset 1" From Bottom Hem, Return extra Fabric & Trim',
            'ticket_notes' => 'Pick Up 4/4, 2" Banding Inset 1" From Bottom Hem, Return extra Fabric & Trim',
            'invoice_notes' => 'Pick Up 4/4, 2" Banding Inset 1" From Bottom Hem, Return extra Fabric & Trim',
            'sidemark' => 'JRD/Presidio/DR',
            'purchase_order' => 'po1111',
            'deposit_amount' => 0.00,
            'company_id' => $company->id,
            'shipping_address_id' => $company->addresses()->first()->id,
            'billing_address_id' => $company->addresses()->first()->id,
            'shipping_method_id' => $company->addresses()->first()->shipping_method->id,
            'credit_terms' => $company->credit_terms,
            'deposit_check_no' => 0,
            'discount_percent' => 0,
            'rush_percent' => 0,
            'boxing_cost' => 0,
            'product_id' => $products->Casual->id,
            'ring_type_id' => $products->Casual->ring_type_id,
            'contact_id' => $company->contacts()->first()->id,
        );

        //add Calico Corners - Denver orders
        $company = Company::where('name', '=', 'Calico Corners - Denver')->firstOrFail();
        $orders[] = array(
            'notes' => '2 Poufs',
            'ticket_notes' => '2 Poufs',
            'invoice_notes' => '2 Poufs',
            'sidemark' => 'Hansen/Remake',
            'purchase_order' => '747791',
            'deposit_amount' => 0.00,
            'company_id' => $company->id,
            'shipping_address_id' => $company->addresses()->first()->id,
            'billing_address_id' => $company->addresses()->first()->id,
            'shipping_method_id' => $company->addresses()->first()->shipping_method->id,
            'credit_terms' => $company->credit_terms,
            'deposit_check_no' => 0,
            'discount_percent' => 0,
            'rush_percent' => 0,
            'boxing_cost' => 0,
            'product_id' => $products->SoftCasual->id,
            'ring_type_id' => $products->SoftCasual->ring_type_id,
            'contact_id' => $company->contacts()->first()->id,
        );

        //add Acme, Inc. orders
        $company = Company::where('name', '=', 'Acme, Inc.')->firstOrFail();
        $orders[] = array(
            'notes' => 'Tassel Trim Bottom',
            'ticket_notes' => 'Tassel Trim Bottom',
            'invoice_notes' => 'Tassel Trim Bottom',
            'sidemark' => 'Dreiling/MBR',
            'purchase_order' => '2222',
            'deposit_amount' => 0.00,
            'company_id' => $company->id,
            'shipping_address_id' => $company->addresses()->first()->id,
            'billing_address_id' => $company->addresses()->first()->id,
            'shipping_method_id' => $company->addresses()->first()->shipping_method->id,
            'credit_terms' => $company->credit_terms,
            'deposit_check_no' => 0,
            'discount_percent' => 0,
            'rush_percent' => 0,
            'boxing_cost' => 0,
            'product_id' => $products->DogEar->id,
            'ring_type_id' => $products->DogEar->ring_type_id,
            'contact_id' => $company->contacts()->first()->id,
        );

        return $orders;
    }
}
