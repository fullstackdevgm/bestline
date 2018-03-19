@extends("layout-plain")

@section('title')
    Bestline - Cutter
@stop

@section('javascript-head')
	@parent
	<script type="text/javascript" src="/js/source/angular/services/bestline-api.js"></script>
	<script type="text/javascript" src="/js/source/angular/controllers/tracking/component/bestline-order-line-checkout.js"></script>
	<script type="text/javascript" src="/js/source/angular/controllers/tracking/cutter.js"></script>
@stop

@section("content")
	<div class="templates templateTrackingCutter"
		ng-controller="TrackingCutterController as vmCutter"
		ng-cloak>
		<div class="row">
			<div class="col-md-12">
				<h1>Cutter Station <span ng-show="vmCutter.loading"><span class="fa fa-circle-o-notch fa-spin"></span></span></h1>
			</div>
		</div>
		<div class="row">
		    <div class="col-md-12">
		        <table id="tbl_orderlist" class="table table-bordered table-hover" ng-show="!vmCutter.loading">

		            <thead>
		                <tr>
		                    <th class="col-md-1">Order ID</th>
		                    <th class="col-md-2">Billing Company</th>
		                    <th class="col-md-1">Sidemark</th>
		                    <th class="col-md-2">Style</th>
		                    <th class="col-md-1">Date Due</th>
		                    <th class="col-md-2">Actions</th>
		                </tr>
		            </thead>

		            <tbody ng-repeat="order in vmCutter.orders">
		            	<tr class="order-row"
		                    ng-class="{'text-primary': order.hover}"
		                    ng-mouseenter="order.hover = true"
		                    ng-mouseleave="order.hover = false">
		                    <td>{[{ order.id }]}</td>
		                    <td>{[{ order.company.name }]}</td>
		                    <td>{[{ order.sidemark }]}</td>
		                    <td>{[{ order.product.name }]} ({[{ order.order_lines.length }]})</td>
		                    <td>{[{ order.date_due }]}</td>
		                    <td class="actions">
		                    	<a class="btn btn-sm btn-info" title="" href=""
		                    		ng-show="order.bulk_checkout && order.bulk_checkout.length > 0"
		                    		ng-click="vmCutter.bulkCheckout(order.bulk_checkout)">
		                    		Checkout All ({[{ order.bulk_checkout.length }]})
		                    	</a>
		                    	<a class="btn btn-sm btn-primary" title="" href=""
		                    		ng-show="order.bulk_checkin && order.bulk_checkin.length > 0"
		                    		ng-click="vmCutter.bulkCheckin(order.bulk_checkin)">
		                    		Check In All ({[{ order.bulk_checkin.length }]})
		                    	</a>
		                    </td>
		                </tr>
		                <tr class="orderline-row"
		                	ng-repeat="orderLine in order.order_lines"
		                	ng-class="{'text-primary': order.hover}"
		                	ng-mouseenter="order.hover = true"
		                	ng-mouseleave="order.hover = false">
		                	<td></td>
		                	<td colspan="4">
		                		<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
		                		<span>Orderline #{[{ orderLine.id }]}: Width: {[{ orderLine.width }]}, Height: {[{ orderLine.height }]}</span>
		                		<span ng-show="orderLine.current_work.id">
		                			<span><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
		                			<span class="text-muted">Checked out at {[{ vmCutter.getDate(orderLine.current_work.start_time) | date:'h:mm a MM-dd-yyyy' }]} by {[{ orderLine.current_work.user.first_name }]} {[{ orderLine.current_work.user.last_name }]}</span>
		                		</span>
		                		<span ng-show="orderLine.current_work.complete_time">
		                			<span><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
		                			<span class="text-muted">Checked in at {[{ vmCutter.getDate(orderLine.current_work.complete_time) | date:'h:mm a MM-dd-yyyy' }]}</span>
		                		</span>
		                		<div ng-show="orderLine.apiErrors.length > 0" class="alert alert-danger clearfix">
		                			<div class="col-md-1">
		                				<i class="fa fa-exclamation-triangle"></i>
		                			</div>
		                			<div class="col-md-11 text-left">
		                				<span ng-repeat="error in orderLine.apiErrors">{[{ error }]}<br/></span>
		                			</div>
		                		</div>
		                	</td>
		                	<td class="actions">
		                		<div  ng-show="!orderLine.loading">
		                			<a class="btn btn-xs btn-info" title="" href=""
		                				ng-show="!orderLine.current_work.id && vmCutter.stationId === orderLine.current_station_id"
		                				ng-click="vmCutter.checkoutOrderLine(orderLine)">
		                				Checkout
		                			</a>
		                			<span ng-show="orderLine.current_work.id && !orderLine.current_work.complete_time">
		                				<a class="btn btn-xs btn-secondary text-muted" title="" href=""
		                					ng-click="vmCutter.undoCheckout(orderLine)">
		                					Cancel
		                				</a>
			                			<a class="btn btn-xs btn-primary" title="" href=""
			                				ng-click="vmCutter.checkinOrderLine(orderLine)">
			                				Check in
			                			</a>
		                			</span>
		                		</div>
		                		<span ng-show="orderLine.loading"><i class="fa fa-circle-o-notch fa-spin"></i></span>
		                	</td>
		                </tr>
		            </tbody>

		            <tr ng-if="vmCutter.orders.length === 0">
		               <td colspan="9" class="text-center">No Results Found</td>
		            </tr>
		        </table>
		    </div>
		</div>
	</div>
@stop

@section("footer")
    @parent
@stop