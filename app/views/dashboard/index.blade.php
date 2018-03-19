@extends('layout')

@section('title')
    Bestline - Dashboard
@stop

@section('stylesheets')
    @parent
@stop

@section('javascript-head')
    @parent
    <script src="/js/vendor/bower_components/angular-smart-table/dist/smart-table.min.js" type="application/javascript"></script>
    <script src="/js/source/angular/services/bestline-api.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-smart-table.js" type="text/javascript"></script>
    <script src="/js/source/angular/controllers/dashboard/index.js" type="application/javascript"></script>
@stop

{{-- Adding the javascript section here instead of at the bottom due to a load order error --}}
@section('javascript')
    @parent
@stop

@section('main')
    <div class="templateDashboardView templates"
        ng-controller="DashboardController as vmDashboard"
        st-table="vmDashboard.displayedOrders"
        st-safe-src="vmDashboard.orders"
        ng-cloak>
        <div class="row">
            <div class="col-md-12">
                <h3 class="pull-left" style="margin-top: 0;">
                    Orders
                    <span ng-show="vmDashboard.loadingOrders"><span class="fa fa-circle-o-notch fa-spin"></span></span>
                </h3>
            </div>
        </div>

        <ul class="nav nav-tabs"
            bestline-smart-table
            st-api="vmDashboard.filterApi">
            <li ng-class="{active: vmDashboard.activeTab === 'home'}" ng-click="vmDashboard.filterTable('home')"><a href="">Home <span class="badge">{[{ vmDashboard.orders.length }]}</span></a></li>
            <li ng-class="{active: vmDashboard.activeTab === 'rush'}" ng-click="vmDashboard.filterTable('rush')"><a href="">Rush <span class="badge">{[{ vmDashboard.counts.rush }]}</span></a></li>
            <li ng-class="{active: vmDashboard.activeTab === 'ships'}" ng-click="vmDashboard.filterTable('ships')"><a href="">Ships <span class="badge">{[{ vmDashboard.counts.ships }]}</span></a></li>
            <li ng-class="{active: vmDashboard.activeTab === 'delivery'}" ng-click="vmDashboard.filterTable('delivery')"><a href="">Delivery <span class="badge">{[{ vmDashboard.counts.delivery }]}</span></a></li>
            <li ng-class="{active: vmDashboard.activeTab === 'pickups'}" ng-click="vmDashboard.filterTable('pickups')"><a href="">Pick-ups <span class="badge">{[{ vmDashboard.counts.pickups }]}</span></a></li>
            <li ng-class="{active: vmDashboard.activeTab === 'unfinalized'}" ng-click="vmDashboard.filterTable('unfinalized')"><a href="">Unfinalized <span class="badge">{[{ vmDashboard.counts.unfinalized }]}</span></a></li>
            <li ng-show="vmDashboard.loadingFilter"><a class="" title="" href=""><span class="fa fa-circle-o-notch fa-spin"></span></a></li>
            <li class="pull-right"><input st-search placeholder="Search" class="input-sm form-control" type="search" ng-model="vmDashboard.searchTerm"/></li>
            <li class="pull-right"><div st-pagination st-items-by-page="vmDashboard.itemsPerPage" st-displayed-pages="vmDashboard.pageNavCount"></div></li>
        </ul>

        <div class="row">
            <div class="col-md-12">
                <table id="tbl_orderlist" class="table table-striped table-bordered table-hover" ng-show="!vmDashboard.loadingOrders">

                    <thead>
                        <tr>
                            <th st-sort="id" class="col-md-1">Order ID <i class="fa fa-sort"></i></th>
                            <th st-sort="company.name" class="col-md-1">Billing Company <i class="fa fa-sort"></i></th>
                            <th st-sort="sidemark" class="col-md-1">Sidemark <i class="fa fa-sort"></i></th>
                            <th st-sort="product.name" class="col-md-1">Style <i class="fa fa-sort"></i></th>
                            <th class="col-md-1">Fabrics</th>
                            <th st-sort="date_received" class="col-md-1">Date Received <i class="fa fa-sort"></i></th>
                            <th st-sort="date_due" class="col-md-1">Date Due <i class="fa fa-sort"></i></th>
                            <th>Status</th>
                            <th st-sort="total" class="col-md-1">Total <i class="fa fa-sort"></i></th>
                            <th st-sort="shipping_method.name" class="col-md-1">Ship <i class="fa fa-sort"></i></th>
                            <th class="col-md-2">Actions</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                    	<tr class="order-row"
                            ng-repeat="order in vmDashboard.displayedOrders"
                            ng-class="{'text-primary': order.hover}"
                            ng-mouseenter="order.hover = true"
                            ng-mouseleave="order.hover = false"
                            ng-click="vmDashboard.orderLinkClick(order)">
                            <td>{[{ order.id }]} <i class="fa fa-exclamation-triangle text-warning" ng-show="order.alerts.length > 0"></i></td>
                            <td sortable="'name'">{[{ order.company.name }]}</td>
                            <td>{[{ order.sidemark }]}</td>
                            <td>{[{ order.product.name }]} ({[{ order.order_lines.length }]})</td>
                            <td>
                                <span ng-repeat="fabric in order.fabrics">
                                    <span>{[{ fabric.type.name.charAt(0) }]}: </span>
                                    <span ng-show="fabric.fabric.owner_company_id">COM</span>
                                    <span ng-show="fabric.fabric.owner_company_id && (fabric.fabric.pattern || fabric.fabric.color)"> - </span>
                                    <span>{[{ fabric.fabric.pattern }]}</span>
                                    <span ng-show="fabric.fabric.color && (fabric.fabric.pattern || fabric.fabric.owner_company_id)"> - </span>
                                    <span>{[{ fabric.fabric.color }]}</span>
                                    <br/>
                                </span>
                            </td>
                            <td>{[{ order.date_received }]}</td>
                            <td>{[{ order.date_due }]}</td>
                            <td>
                                <span ng-show="order.finalized.id">Finalized</span>
                                <span ng-show="!order.finalized.id">Open</span>
                            </td>
                            <td>
                                $
                                <span ng-show="order.finalized.total">{[{ order.finalized.total }]}</span>
                                <span ng-show="!order.finalized.total">{[{ order.total }]}</span>
                            </td>
                            <td>{[{ order.shipping_method.name }]}</td>
                            <td class="actions">
                                <div class="alert alert-danger" ng-show="order.apiError">{[{ order.apiError }]}</div>
                                <div ng-show="order.loading"><span class="fa fa-circle-o-notch fa-spin fa-2x"></span></div>
                                <div ng-show="!order.loading">

                                    <div ng-if="order.is_quote">
                                        <a class="btn btn-default btn-sm " style="margin-right: 5px;" href="" ng-click="vmDashboard.confirm(order); $event.stopPropagation();">Confirm</a>
                                        <a class="btn btn-default btn-sm" target="_blank" href="{[{ '/order/quote/' + order.id }]}" ng-click="$event.stopPropagation();">Quote</a>
                                    </div>

                                    <div ng-if="!order.finalized.id && !order.is_quote">
                                        <div>
                                            <a class="btn btn-default btn-sm" href="" ng-click="vmDashboard.alertsBlockingFinalization(order).length > 0||vmDashboard.finalize(order); $event.stopPropagation();">
                                                <span ng-show="vmDashboard.alertsBlockingFinalization(order).length === 0">Finalize</span>
                                                <span ng-show="vmDashboard.alertsBlockingFinalization(order).length > 0"><s>Finalize</s> <i class="fa fa-exclamation-triangle text-warning" ></i></span>
                                            </a>
                                            <a class="btn btn-default btn-sm" target="_blank"
                                                href="{[{ '/order/confirmation/' + order.id }]}"
                                                ng-hide="vmDashboard.alertsBlockingFinalization(order).length > 0"
                                                ng-class="{ 'red-text': order.in_confirmation_timeframe }"
                                                ng-click="$event.stopPropagation();">
                                                Confirmation
                                            </a>
                                        </div>
                                    </div>

                                    <div ng-if="order.finalized.id">
                                        <a class="btn btn-default btn-sm" href="{[{ '/order/final/ticket?order=' + order.id }]}" ng-click="$event.stopPropagation();">Tickets</a>
                                        <a class="btn btn-default btn-sm" target="_blank" href="{[{ '/order/invoice/' + order.id }]}" ng-click="$event.stopPropagation();">Invoice</a>
                                        <a class="btn btn-danger btn-sm " style="margin-right: 5px;" href="" ng-click="vmDashboard.unfinalize(order); $event.stopPropagation();">Unfinalize</a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a class="btn text-danger" href=""
                                    ng-show="!order.confirmDelete && !order.loading"
                                    ng-click="order.confirmDelete = true; $event.stopPropagation();">
                                    <i class="fa fa-trash"></i>
                                </a>
                                <a class="btn btn-default btn-sm btn-block" title="" href=""
                                    ng-show="order.confirmDelete"
                                    ng-click="$event.stopPropagation(); order.confirmDelete = false;">
                                    Cancel
                                </a>
                                <a class="btn text-danger btn-sm" title="" href=""
                                    ng-show="order.confirmDelete"
                                    ng-click="vmDashboard.deleteOrder(order); $event.stopPropagation(); order.confirmDelete = false;">
                                    Confirm <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <tr ng-if="vmDashboard.displayedOrders.length === 0">
                           <td colspan="12" class="text-center">No Results Found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                <div st-pagination st-items-by-page="vmDashboard.itemsPerPage" st-displayed-pages="vmDashboard.pageNavCount"></div>
            </div>
        </div>
    </div>
@stop