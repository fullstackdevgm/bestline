@extends('layout')

@section('title')
    Bestline - Create order
@stop

@section('javascript-head')
    @parent
    <script src="/js/source/angular/services/bestline-api.js" type="application/javascript"></script>
    <script src="/js/source/angular/services/bestline-forms.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-isolate-form.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-address-form.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-contact-form.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-fabric-input-rows.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-option-input-rows.js" type="application/javascript"></script>
    <script src="/js/source/angular/controllers/order/order-create.js" type="application/javascript"></script>
@stop

@section('javascript')
    @parent
    <script src="/js/vendor/bootstrap-datepicker.js" type="application/javascript"></script>
@stop

@section('main')

    <div class="templates templateOrderCreate" ng-controller="OrderCreateController" ng-cloak>

        <h1>Create Order <span class="label label-primary" ng-show="order.is_quote">Quote</span></h1>

        <div ng-form="orderForm">
            <div class="order-create well">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Company</h4>
                        <div class="form-group">
                            <select class="form-control input-sm"
                                ng-show="!order.loading && selectOptions.companies.length > 0"
                                ng-model="order.company_id"
                                ng-change="changeCompany(order.company_id)"
                                ng-options="company.id as company.name for company in selectOptions.companies">
                                <option value="">Select a company...</option>
                            </select>
                            <div ng-show="order.loading || selectOptions.companies.length === 0"><i class="fa fa-circle-o-notch fa-spin fa-2x"></i></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h4>
                            Contact
                            <span class="pull-right small" ng-show="order.company_id">
                                <a href="" ng-click="newContact(order.company_id)"><i class="fa fa-plus-square text-orange"></i> Add</a>
                            </span>
                        </h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select id="contact" name="contact_select" class="form-control input-sm"
                                        ng-show="!loadingContacts"
                                        ng-model="contact"
                                        ng-disabled="!contacts || contacts.length === 0"
                                        ng-change="onContactChange(contact)"
                                        ng-options="c.first_name for c in contacts">
                                        <option value="">Select a contact...</option>
                                    </select>
                                    <div ng-show="loadingContacts"><i class="fa fa-circle-o-notch fa-spin fa-2x"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <bestline-contact-form bcf="{contact: contact}" ng-if="contact"></bestline-contact-form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" id="billing_info">
                        <h4>Billing Information

                            <span class="pull-right small" ng-show="order.company_id"><a href="" ng-click="newBillingAddress(order)"><i class="fa fa-plus-square text-orange"></i> Add</a></span>
                        </h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select name="billing_address_select" class="form-control input-sm"
                                        ng-show="!loadingAddresses"
                                        ng-model="billing_address"
                                        ng-disabled="!addresses || addresses.length === 0"
                                        ng-change="onBillingAddressChange(billing_address)"
                                        ng-options="address.address1 for address in addresses">
                                        <option value="">Select a billing address...</option>
                                    </select>
                                    <div ng-show="loadingAddresses"><i class="fa fa-circle-o-notch fa-spin fa-2x"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div bestline-address-form="{address: billing_address, shippingMethods: billing_address.shipping_methods, states: billing_address.states_select_options}" ng-if="billing_address"></div>
                            </div>
                        </div>
                        <h4>
                            Shipping Information

                            <span class="pull-right small addShippingAddress" ng-show="order.company_id"><a href="" ng-click="newShippingAddress(order)"><i class="fa fa-plus-square text-orange"></i> Add</a></span>
                            <span class="pull-right small">
                                {{ Form::label('copy_billing', 'Same As Billing:') }}
                                <input type="checkbox" id="copy_billing"
                                    ng-model="order.copyBilling"
                                />
                            </span>
                        </h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select name="shipping_address_select" class="form-control input-sm"
                                        ng-show="!loadingAddresses"
                                        ng-model="shipping_address"
                                        ng-change="onShippingAddressChange(shipping_address)"
                                        ng-disabled="!addresses || addresses.length === 0 || order.copyBilling"
                                        ng-options="address.address1 for address in addresses">
                                        <option value="">Select a shipping address...</option>
                                    </select>
                                    <div ng-show="loadingAddresses"><i class="fa fa-circle-o-notch fa-spin fa-2x"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div bestline-address-form="{address: shipping_address, shippingMethods: shipping_address.shipping_methods, states: shipping_address.states_select_options}" ng-if="shipping_address"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="well">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Order Information</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="sidemark">Side Mark:</label>
                                    <input type="text" ng-model="order.sidemark" class='form-control input-sm'/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_received">Date Received:</label>
                                    <input ng-model="order.date_received" type="text" data-provide='datepicker' id='order_date_received' class='form-control input-sm' data-date-format='yyyy-mm-dd' />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_due">Date Due:</label>
                                    <input ng-model="order.date_due" type="text" data-provide='datepicker' id='order_date_received' class='form-control input-sm' data-date-format='yyyy-mm-dd' />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="purchase_order">PO:</label>
                                    <input type="text" ng-model="order.purchase_order" class='form-control input-sm'/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Ingredients</h4>
                            </div>
                        </div>
                        <div class="well" style="background-color: #fff">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="product_id">Style</label>
                                        <select id="product_id" class="form-control input-sm"
                                            ng-show="!loadingSelectOptions"
                                            ng-model="order.product_id"
                                            ng-change="onProductChange(order.product_id)"
                                            ng-options="product.id as product.name for product in selectOptions.products">
                                            <option value="">Select a product...</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="ring_type_id">Ring Type</label>
                                        <select id="ring_type_id" class="form-control input-sm"
                                            ng-disabled="!order.product_id"
                                            ng-show="!loadingSelectOptions"
                                            ng-model="order.ring_type_id"
                                            ng-options="ringType.id as ringType.description for ringType in selectOptions.ring_types">
                                            <option value="">Select a ring type...</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- closes row -->
            </div>

            <div class="well" id="data_entry_notes">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Fabrics</h4>
                    </div>
                    <div class="col-md-6">
                        <h4>Fabric Options <a href="" class="btn btn-primary btn-xs pull-right" ng-click="addNewFabric()">Add Fabric</a></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="well" style="background-color: #fff">
                            <bestline-fabric-input-rows order-fabrics="order.fabrics" order-company-id="order.company_id" api="fabricApi"></bestline-fabric-input-rows>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        <h4>Option Defaults <a href="" id="addDefaultOptionBtn" class="btn btn-primary btn-xs pull-right" ng-click="addNewOption()">Add Option</a></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        <div class="well" id="default_options_list" style="background-color: #fff">

                           <bestline-option-input-rows order-options="order.default_options" api="optionsApi"></bestline-option-input-rows>
                        </div>
                    </div>

                </div>
            </div>

            <div class="well" id="data_entry_notes">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Notes</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="notes_order">Internal Notes:</label>
                            <textarea ng-model="order.notes" id="notes_order" class='form-control input-sm' rows="10"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="notes_ticket">Manufacturing Notes:</label>
                            <textarea ng-model="order.ticket_notes" class='form-control input-sm' rows="10"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="notes_invoice">External Notes:</label>
                            <textarea ng-model="order.invoice_notes" class='form-control input-sm' rows="10"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="order-create">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger" ng-if="order.apiError">
                            {[{ order.apiError }]}
                            <i class="fa fa-remove pull-right" ng-click="order.apiError = null;"></i>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-8 col-md-4 text-right">
                        {{ link_to_route('dashboard', 'Cancel', null, array('class' => 'disable_unload_warning btn btn-default order_cancel_btn')) }}
                        <a class="btn btn-primary" href=""
                            ng-show="!savingOrder"
                            ng-click="saveOrder(order)">
                            Submit
                        </a>
                        <span ng-show="savingOrder"><i class="fa fa-circle-o-notch fa-spin fa-2x"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
