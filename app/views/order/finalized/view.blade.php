@extends('layout')

@section('title')
    Bestline - View finalized order
@stop

@section('javascript-head')
    @parent
    <script src="/js/source/angular/services/bestline-api.js" type="application/javascript"></script>
    <script src="/js/source/angular/controllers/order/finalized/view.js" type="application/javascript"></script>
@stop

@section('main')

<?php use Bestline\Utils; ?>
<div id="bestline_order" ng-controller="FinalizedViewController as fvController" ng-cloak>
    
    <h1>View Finalized Order</h1>
    <div class="order-create" >
        
        <div class="well">
            <h4>Order Information</h4>
            <div class="row">
                <div class="col-md-3">
                    {{ Form::label('sidemark', 'Side Mark:') }}
                    {{{ $order->sidemark }}}
                </div>
                <div class="col-md-3">
                    {{ Form::label('date_received', 'Date Received:') }}
                    {{{ date('m/d/Y', strtotime($order->date_received)) }}}
                </div>
                <div class="col-md-3">
                    {{ Form::label('date_due', 'Date Due:') }}
                    {{{ date('m/d/Y', strtotime($order->date_due)) }}}
                </div>
            </div>
        </div>

        <div class="order-create2 well" id="billing_info">
            <h4>Customer Information</h4>
            <div class="row">
                <div class="col-md-3">
                    <h6>{{ Form::label('BillingInformation', ' Billing Info') }}</h6>
                    {{{ $order->company->name }}}<br/>
                    {{{ $order->billingAddress->address1 }}}<br/>
                    @if (!empty($order->billingAddress->address2))
                        {{{ $order->billingAddress->address2 }}}<br/>
                    @endif
                    {{{ $order->billingAddress->city . ', ' . $order->billingAddress->state . ' ' . $order->billingAddress->zip }}}
                    <br/>
                    <br/>
                   
                </div>
                @if($order->contact)
                    <div class="col-md-3">
                        <h6>Contact</h6>
                        {{{ $order->contact->full_name }}}<br/>
                        {{{ $order->contact->phone_number->number }}}<br/>
                        {{{ $order->contact->email->email }}}
                    </div>
                @endif
                <div class="col-md-3">
                    <h6>{{ Form::label('ShippingInformation', ' Shipping Info') }}</h6>
                    {{{ $order->company->name }}}<br/>
                    {{{ $order->shippingAddress->address1 }}}<br/>
                    @if (!empty($order->shippingAddress->address2))
                        {{{ $order->shippingAddress->address2 }}}<br/>
                    @endif
                    {{{ $order->shippingAddress->city . ', ' . $order->shippingAddress->state . ' ' . $order->shippingAddress->zip }}}<br/>
                </div>
                <div class="col-md-3">
                    <h6>{{ Form::label('CreditInfo', 'Credit Info') }}</h6>
                    {{ Form::label('credit_terms', 'Credit Terms:') }}
                    {{{ Company::getCreditTermsDesc($order->credit_terms) }}}<br/>
                    {{ Form::label('account_no', 'Account No:') }}
                    {{{ $order->company->account_no  }}}<br/>
                    {{ Form::label('customer_type', 'Customer Type:') }}
                    {{{ $order->ct_name }}}
                </div>
            </div>
        </div>
        <div class="well">
            <h4>Ingredients</h4>
            <div class="row">
                <div class="col-md-6">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fabric Type</th>
                                <th>Name</th>
                                <th>Option</th>
                                <th>Sub Option</th>
                            </tr>
                         </thead>
                         <tbody>
                         @foreach($order->fabrics as $fabric)
                            <tr>
                                <td>{{ $fabric->type->name }}</td>
                                <td>{{ $fabric->fabric->name }}</td>
                                <td>@if(count($fabric->options) > 0){{ $fabric->options[0]->option->name }}@endif</td>
                                <td>@if(count($fabric->options) > 0){{ $fabric->options[0]->sub_option->name }}@endif</td>
                            </tr>
                         @endforeach
                         </tbody>
                    </table>
                </div>
                <div class="col-md-4 col-md-offset-2">
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="2">Default Options</th>
                                @foreach($order->defaultOptions as $defaultOption)
                                    <tr>
                                        <td>{{ $defaultOption->option->name }}</td>
                                        <td>{{ $defaultOption->subOption->name }}</td>
                                    </tr>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="well" id="lineItems">
            <div class="row">
                <div class="col-md-12">
                    <h4>Order Lines</h4>
                </div>
                 <div class="orderLines">
                   @if($order->orderLines->count() > 0)
                     @foreach($order->orderLines as $index => $orderLine)
                       @include('order.finalized.orderline', compact('orderLine', 'index'))
                     @endforeach
                   @endif
                 </div>
            </div>
        </div>
        <div id="order-totals" class="well">
            <h4>Order Totals</h4>
            <div class="row">
                <div class="col-md-offset-4 col-md-4">
                    {{ Form::label('purchase_order', 'PO:') }}
                    {{ $order->purchase_order }}
                </div>
                <div class="col-md-4 ">
                    {{ Form::label('subtotal', 'Subtotal:') }} 
                    {{ $order->finalized->subtotal }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-4 col-md-4">
                    {{ Form::label('deposit_check_no', 'Deposit Chk #:') }}
                    {{ $order->deposit_check_no }}
                </div>
                <div class="col-md-4">
                    {{ Form::label('deposit_amount', 'Deposit Amt:') }}
                    {{ $order->deposit_amount }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 pull-right">
                    <strong>Discount ({{ $order->discount_percent }}%):</strong>
                    {{ $order->finalized->discount_total }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 pull-right">
                    <strong>Rush ({{ $order->rush_percent }}%):</strong>
                    {{ $order->finalized->rush_total }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-md-offset-8">
                    {{ Form::label('boxing_amt', 'Boxing Cost:') }}
                    {{ $order->boxing_cost }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 pull-right">
                    <strong>Shipping ({{ $order->sm_name }}):</strong>
                    {{ $order->shipping_amount }}
                </div>
            </div>
            <div class="row">
            </div>
            <div class="row">
                <div class="col-md-4 col-md-offset-8">
                    {{ Form::label('total', 'Total:') }}
                    {{ $order->finalized->total }}
                </div>
            </div>
            <div class="row">&nbsp;</div>
        </div>
    </div>
</div>

@stop