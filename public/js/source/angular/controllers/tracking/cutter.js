(function ($, angular) { 'use strict';

/**
 * @ngdoc function
 * @name bestline.controller:TrackingCutterController
 * @description
 */
angular.module('bestline').controller('TrackingCutterController', TrackingCutterController);
function TrackingCutterController(
	$scope,
	$q,
	$uibModal,
	bestlineApi
){

	var vm = this;

	function setup(){

		vm.orders = [];
		vm.stationId = 1; //we need to get this by name

		loadData();
		viewApi();
	}
	function viewApi(){
		vm.checkoutOrderLine = function(orderLine){
			var orderLines = [orderLine];
			checkoutModal(orderLines, vm.users);
		};
		vm.checkinOrderLine = function(orderLine){

			orderLine.apiErrors = [];

			if(!orderLine.current_work.id){
				orderLine.apiErrors.push('This order line doesn\'t have current work.');
				return false;
			}
			checkinOrderLine(orderLine);
		};
		vm.bulkCheckin = function(orderLines){
			angular.forEach(orderLines, eachOrderLine);
			function eachOrderLine(orderLine){
				checkinOrderLine(orderLine);
			}
		};
		vm.bulkCheckout = function(orderLines){
			checkoutModal(orderLines, vm.users);
		};
		vm.getDate = function(mysqlDateTime){
			if(mysqlDateTime){
				var dateParts = mysqlDateTime.split(/[- :]/);
				return new Date(Date.UTC(dateParts[0], dateParts[1]-1, dateParts[2], dateParts[3], dateParts[4], dateParts[5]));
			}
		};
		vm.undoCheckout = function(orderLine){
			undoCheckoutOrderLine(orderLine);
		};
	}
	function loadData(){

		vm.loading = true;
		$q.all([getOrders(), getUsers()]).then(loadDataSuccess, loadDataError);
		function loadDataSuccess(){
			vm.loading = false;
		}
		function loadDataError(){
			vm.loading = false;
		}
	}
	function getOrders(){

		return $q(getOrdersPromise);
		function getOrdersPromise(getOrdersResolve, getOrdersReject){

			bestlineApi.station(vm.stationId).orders().then(getOrdersSuccess, getOrdersError);
			function getOrdersSuccess(response){

				vm.orders = response.data;
				checkBulkActionsForAllOrders();
				getOrdersResolve(response);
			};
			function getOrdersError(response){
				getOrdersReject(response);
			};
		};
	}
	function getUsers(){

		return $q(getUsersPromise);
		function getUsersPromise(getUsersResolve, getUsersReject){

			bestlineApi.station(vm.stationId).users().then(getUsersSuccess, getUsersError);
			function getUsersSuccess(response){

				vm.users = response.data;
				getUsersResolve(response);
			};
			function getUsersError(response){
				getUsersReject(response);
			};
		};
	}
	function checkoutModal(orderLines, users){
		var checkoutModal = $uibModal.open({
	      	templateUrl: '/js/source/angular/views/components/bestline-order-lines-checkout.html',
	      	controller: 'BestlineOrderLinesCheckout',
	      	controllerAs: 'vmCheckout',
	      	resolve: {
	        	orderLines: function () {
	          		return orderLines;
	        	},
	        	users: function(){
	        		return users;
	        	},
	      	}
	    });

	    checkoutModal.result.then(checkoutSuccess);
	    function checkoutSuccess(){
	    	checkBulkActionsForAllOrders();
	    }
	}
	function undoCheckoutOrderLine(orderLine){

		orderLine.loading = true;
		orderLine.apiErrors = [];

		return $q(undoCheckoutOrderLinePromise);
		function undoCheckoutOrderLinePromise(undoCheckoutOrderLineResolve, undoCheckoutOrderLineReject){

			bestlineApi.order(orderLine.order.id).orderLine(orderLine.id).work(orderLine.current_work.id).undo().then(undoCheckoutOrderLineSuccess, undoCheckoutOrderLineError);
			function undoCheckoutOrderLineSuccess(response){

				orderLine.current_work = null;
				orderLine.loading = false;
				checkBulkActionsForAllOrders();
				undoCheckoutOrderLineResolve(response);
			};
			function undoCheckoutOrderLineError(response){
				orderLine.apiErrors.push(response.data.error.message);
				orderLine.loading = false;
				undoCheckoutOrderLineReject(response);
			};
		};
	}
	function checkinOrderLine(orderLine){

		orderLine.loading = true;
		orderLine.apiErrors = [];

		return $q(checkinOrderLinePromise);
		function checkinOrderLinePromise(checkinOrderLineResolve, checkinOrderLineReject){

			bestlineApi.order(orderLine.order.id).orderLine(orderLine.id).work(orderLine.current_work.id).checkin().then(checkinOrderLineSuccess, checkinOrderLineError);
			function checkinOrderLineSuccess(response){

				orderLine.current_work = response.data;
				orderLine.loading = false;
				checkBulkActionsForAllOrders();
				checkinOrderLineResolve(response);
			};
			function checkinOrderLineError(response){
				orderLine.loading = false;
				orderLine.apiErrors.push(response.data.error.message);
				checkinOrderLineReject(response);
			};
		};
	}
	function checkBulkActionsForAllOrders(){
		angular.forEach(vm.orders, eachOrder);
		function eachOrder(order, index){
			checkOrdersBulkActions(order, index);
		}
	}
	function checkOrdersBulkActions(order, index){

		order.bulk_checkout = [];
		order.bulk_checkin = [];
		order.completed_orderlines = [];

		angular.forEach(order.order_lines, eachOrderLine);
		function eachOrderLine(orderLine){
			if(!orderLine.current_work && orderLine.current_station_id === vm.stationId){
				order.bulk_checkout.push(orderLine);
			}
			if(orderLine.current_work && !orderLine.current_work.complete_time){
				order.bulk_checkin.push(orderLine);
			}
			if(orderLine.current_work && orderLine.current_work.complete_time){
				order.completed_orderlines.push(orderLine);
			}
		}

		//don't show button if all orderlines don't need the same action
		if(order.bulk_checkout.length !== order.order_lines.length){
			order.bulk_checkout = [];
		}
		if(order.bulk_checkin.length !== order.order_lines.length){
			order.bulk_checkin = [];
		}

		//hide order if order lines are complete
		if(order.completed_orderlines.length === order.order_lines.length){
			vm.orders.splice(index, 1);
		}
	}
	setup();
}

}(window.jQuery || window.$, window.angular));
