@extends('layout')

@section('title')
    Bestline - Edit order step 2
@stop

@section('stylesheets')
    @parent
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css" type="text/css"/>
@stop

@section('javascript-head')
    @parent
    <script src="/js/source/angular/services/bestline-api.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-option-input-rows.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-api-errors.js" type="application/javascript"></script>
    <script src="/js/source/angular/controllers/order/order-step2.js" type="application/javascript"></script>
    <script src="/js/source/angular/controllers/order/order-line.js" type="application/javascript"></script>
@stop

@section('javascript')
    @parent
    <script type="application/javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
    <script>

        $(document).ready(function() {
            $('#transactionsTable').dataTable({
          	  "sPaginationType": "full_numbers",
              "bProcessing": false,
              "sAjaxSource": "/api/order/transactions/datatable/{{ $order->id }}",
              "bServerSide": true
            });
        });
    </script>
@stop

@section('main')

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
    <li role="presentation"><a href="#history" aria-controls="history" role="tab" data-toggle="tab">History</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">
       <div id="bestline_order" class="templates templateOrderStep2" ng-controller="OrderStepTwoController as step2" ng-cloak>

            <ng-include src="'formWarnings.script'"></ng-include>
            <ng-include src="'formErrors.script'"></ng-include>

            <div class="row">
                <div class="col-md-8">
                    <h1 class='b-margin b-top-0'>Edit Order - {[{ step2.order.sidemark }]} <span class="label label-danger" ng-show="step2.order.is_rush">Rush</span> <span class="label label-primary" ng-show="step2.order.is_quote">Quote</span></h1>
                </div>
                <div class="col-md-4">
                    <ng-include src="'saveForm.script'" class='b-margin b-top'></ng-include>
                </div>
            </div>

            <div class="order-create" id="data_entry_other_invoice_info">

                <div class="order-create2 well" id="billing_info">
                    <h4>
                        Customer Information
                        <a href="{{ URL::route('order.create', ['order' => $order->id]) }}" class="pull-right" ><i class="fa fa-fw fa-edit"></i> Edit</a>
                    </h4>
                    <div class="row">
                        <div class="col-md-3">
                            <h6>Billing Info</h6>
                            <span ng-show="step2.order.billing_address">
                                <span ng-show="step2.order.company.name">{[{ step2.order.company.name }]}<br/></span>
                                <span ng-show="step2.order.billing_address.address1">{[{ step2.order.billing_address.address1 }]}<br/></span>
                                <span ng-show="step2.order.billing_address.address2">{[{ step2.order.billing_address.address2 }]}<br/></span>
                                <span>
                                    <span ng-show="step2.order.billing_address.city">{[{ step2.order.billing_address.city }]},</span>
                                    <span ng-show="step2.order.billing_address.state">{[{ step2.order.billing_address.state }]}</span>
                                    <span ng-show="step2.order.billing_address.zip">{[{ step2.order.billing_address.zip }]}</span>
                                    <br/>
                                </span>
                            </span>
                            <span ng-show="step2.order.contact">
                                <h6>Contact</h6>
                                <span ng-show="step2.order.contact.full_name">{[{ step2.order.contact.full_name }]}<br/></span>
                                <span ng-show="step2.order.contact.phone_number.number ">{[{ step2.order.contact.phone_number.number }]}<br/></span>
                                <span ng-show="step2.order.contact.email.email">{[{ step2.order.contact.email.email }]}<br/></span>
                            </span>
                        </div>
                        <div class="col-md-3">
                            <h6>Shipping Info</h6>
                            <span ng-show="step2.order.shipping_address">
                                <span ng-show="step2.order.company.name">{[{ step2.order.company.name }]}<br/></span>
                                <span ng-show="step2.order.shipping_address.address1">{[{ step2.order.shipping_address.address1 }]}<br/></span>
                                <span ng-show="step2.order.shipping_address.address2">{[{ step2.order.shipping_address.address2 }]}<br/></span>
                                <span>
                                    <span ng-show="step2.order.shipping_address.city">{[{ step2.order.shipping_address.city }]},</span>
                                    <span ng-show="step2.order.shipping_address.state">{[{ step2.order.shipping_address.state }]}</span>
                                    <span ng-show="step2.order.shipping_address.zip">{[{ step2.order.shipping_address.zip }]}</span>
                                    <br/>
                                </span>
                            </span>
                        </div>
                        <div class="col-md-3">
                            <h6>Order Information</h6>
                            <span><b>Date Received:</b> {[{ step2.order.date_received }]}<br/></span>
                            <span><b>Date Due:</b> {[{ step2.order.date_due }]}<br/></span>
                            <span><b>Side Mark:</b> {[{ step2.order.sidemark }]}<br/></span>
                            <span><b>Product:</b> {[{ step2.order.product.name }]}<br/></span>
                            <span><b>Ring Type:</b> {[{ step2.order.ring_type.description }]}</span>
                        </div>
                        <div class="col-md-3">
                            <h6>Credit Info</h6>
                            <span><b>Credit Terms:</b> {[{ step2.order.company.credit_terms_description }]}<br/></span>
                            <span><b>Account No:</b> {[{ step2.order.company.account_no }]}<br/></span>
                            <span><b>Customer Type:</b> {[{ step2.order.customer_type.name }]}<br/></span>
                        </div>
                    </div>
                </div>

                <div class="well">
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
                                    <tr ng-repeat="fabric in step2.order.fabrics">
                                        <td>{[{ fabric.type.name }]}</td>
                                        <td>{[{ fabric.fabric.name }]}</td>
                                        <td><span ng-show="fabric.options && fabric.options.length > 0">{[{ fabric.options[0].option.name }]}</span></td>
                                        <td><span ng-show="fabric.options && fabric.options.length > 0">{[{ fabric.options[0].sub_option.name }]}</span></td>
                                    </tr>
                                 </tbody>
                            </table>
                        </div>
                        <div class="col-md-4 col-md-offset-2">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th colspan="2">Default Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="orderOption in step2.order.default_options">
                                        <td>{[{ orderOption.option.name }]}</td>
                                        <td>{[{ orderOption.sub_option.name }]}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div ng-form="step2.step2Form">

                    <div class="well">
                        <div ng-if="step2.addOrderLineError">
                            <div class="alert alert-danger">
                                <i class="fa fa-exclamation-triangle"></i>
                                {[{ step2.addOrderLineError }]}
                                <i class="fa fa-remove pull-right" ng-click="step2.addOrderLineError = null"></i>
                            </div>
                        </div>

                        <ng-include src="'addRow.script'" ng-if="true" onLoad="addRowTitle='Order Lines'"></ng-include>
                        <div id="orderLines">
                            <ng-include src="'/js/source/angular/views/order/order-line.html'"></ng-include>
                        </div>
                        <ng-include src="'addRow.script'" ng-if="step2.order.order_lines.length > 0" onLoad="addRowTitle=''"></ng-include>

                        <script type="text/ng-template" id="addRow.script">
                            <div class="row">
                                <div class="col-md-10">
                                    <h4>{[{ addRowTitle }]}</h4>
                                </div>
                                <div class="col-md-2 text-right">
                                    <div ng-show="step2.gettingNewOrderLine"><span class="fa fa-circle-o-notch fa-spin fa-2x"></span></div>
                                    <div class="dropdown" ng-hide="step2.gettingNewOrderLine">
                                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                            <i class="fa fa-plus-square text-orange"></i>
                                            <span class="text-orange">Add Line</span>
                                        </button>
                                      <ul class="dropdown-menu dropdown-menu-left">
                                        <li><a class="text-orange" href="" ng-click="step2.addOrderLine(step2.order.id, 'shade')">Shade</a></li>
                                        <li><a class="text-orange" href="" ng-click="step2.addOrderLine(step2.order.id, 'valance')">Valance</a></li>
                                        <li><a class="text-orange" href="" ng-click="step2.addOrderLine(step2.order.id, 'both')">Shade & Valance</a></li>
                                      </ul>
                                    </div>
                                </div>
                            </div>
                        </script>
                    </div>

                    <div id="order-totals" class="well">
                        <h4>Order Totals</h4>
                        <div class="row">
                            <div class="col-md-offset-4 col-md-4">
                                <div class="form-group form-inline">
                                    <label for="purchase_order">PO:</label>
                                    <input type="text" class="form-control input-sm" ng-model="step2.order.purchase_order"/>
                                </div>
                            </div>
                            <div class="col-md-4 ">
                                <div class="form-group form-inline">
                                    <label for="subtotal">Subtotal:</label>
                                    <input type="text" class="form-control input-sm" readonly value="{[{ step2.subtotal() }]}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-offset-4 col-md-4">
                                <div class="form-group form-inline">
                                    <label for="deposit_check_no">Deposit Chk #:</label>
                                    <input type="text" class="form-control input-sm" ng-model="step2.order.deposit_check_no"/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-inline">
                                    <label for="deposit_amount">Deposit Amt:</label>
                                    <input type="text" class="form-control input-sm" ng-model="step2.order.deposit_amount"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 pull-right">
                                <div class="form-group form-inline">
                                    <select class="form-control input-sm"
                                        ng-model="step2.order.discount_percent"
                                        ng-options="discountOption.value as discountOption.text for discountOption in step2.discountSelectOptions">
                                    </select>
                                    <input type="text" class="form-control input-sm" readonly value="{[{ step2.discount() }]}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 pull-right">
                                <div class="form-group form-inline">
                                    <label for="is_rush">Is This Order Rushed?</label>
                                    <input id="is_rush" type="checkbox" ng-model="step2.order.is_rush" ng-true-value="1" ng-false-value="0"/>
                                </div>
                            </div>
                        </div>
                        <div class="row" ng-show="step2.order.is_rush">
                            <div class="col-md-4 pull-right">
                                <div class="form-group form-inline">
                                    <select class="form-control input-sm"
                                        ng-model="step2.order.rush_percent"
                                        ng-options="rushOption.value as rushOption.text for rushOption in step2.rushSelectOptions">
                                    </select>
                                    <input type="text" class="form-control input-sm" readonly value="{[{ step2.rush() }]}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-md-offset-8">
                                <div class="form-group form-inline">
                                    <label for="boxing_amt">Boxing Cost:</label>
                                    <input type="text" class="form-control input-sm" ng-model="step2.order.boxing_cost" value="{[{ step2.rush() }]}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 pull-right">
                                <div class="form-group form-inline">
                                    <select class="form-control input-sm"
                                        ng-model="step2.order.shipping_method_id"
                                        ng-options="shippingMethod.id as shippingMethod.name for shippingMethod in step2.selectOptions.shipping_methods">
                                        <option value="">Select shipping method...</option>
                                    </select>
                                    <input type="text" class="form-control input-sm" ng-model="step2.order.shipping_amount"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-md-offset-8">
                                <div class="form-group form-inline">
                                    <label for="total">Total:</label>
                                    <input type="text" class="form-control input-sm" readonly value="{[{ step2.total() }]}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>
                    </div>

                </div>
            </div>

            <ng-include src="'formWarnings.script'"></ng-include>
            <ng-include src="'formErrors.script'"></ng-include>
            <ng-include src="'saveForm.script'"></ng-include>

            <script type="text/ng-template" id="formErrors.script">
                <div class="alert alert-danger" ng-if="step2.order.apiError">
                    {[{ step2.order.apiError }]}
                    <i class="fa fa-remove pull-right" ng-click="step2.order.apiError = undefined;"></i>
                </div>
            </script>

            <script type="text/ng-template" id="saveForm.script">
                <div ng-init="linkToDashboard = '{{ route('dashboard'); }}'">
                    <div class="text-right">
                        <a class="btn btn-default order_cancel_btn" href="{[{ linkToDashboard }]}">Cancel</a>
                        <a href="" id="order_save_button" class="btn btn-primary"
                            ng-click="step2.save(step2.order)">
                            Save
                        </a>
                        <span ng-show="step2.savingOrder"><i class="fa fa-circle-o-notch fa-spin fa-2x"></i></span>
                    </div>
                </div>
            </script>

            <script type="text/ng-template" id="formWarnings.script">
                <div class="alert alert-warning" ng-show="step2.order.alerts.length > 0">
                    <div class="row">
                        <div class="col-md-12">
                            <span ng-repeat="alert in step2.order.alerts"><i class="fa fa-exclamation-triangle text-warning"></i> {[{ alert.description }]}<br/></span>
                        </div>
                    </div>
                </div>
            </script>
        </div>

    </div>
    <div role="tabpanel" class="tab-pane" id="history">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped" id="transactionsTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>User</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
  </div>

@stop

