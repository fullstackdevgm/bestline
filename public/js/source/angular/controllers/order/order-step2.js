(function ($, angular) {
'use strict';

/**
 * @ngdoc function
 * @name bestline.controller:OrderStepTwoController
 * @description
 */
angular.module('bestline').controller('OrderStepTwoController', OrderStepTwoController);

function OrderStepTwoController(
	$scope,
	$window,
	$location,
	$q,
	$timeout,
	filterFilter,
	bestlineApi,
	smoothScroll
){

	var vm = this;

	function setup(){

		vm.order = {};
		vm.orderId = Number($location.path().split("/")[2]);
		vm.addOrderLineError = null;
		vm.gettingNewOrderLine = false;

		if(!isNaN(vm.orderId)){
			getOrder(vm.orderId);
		}
		viewApi();
		setupSelectOptions();
		setupTotalValues();
		confirmNavigationIfFormIsDirty();
	}
	function viewApi(){

		vm.addOrderLine = function(orderId, type){

			vm.addOrderLineError = null;

			if(!vm.order.product){
				vm.addOrderLineError = 'You must select a product to add order lines.';
				return false;
			}

			getNewOrderLine(orderId).then(newOrderLineSuccess);

			function newOrderLineSuccess(newOrderLine){
				//show inputs correctly based on shade type
				switch(type) {
				    case 'valance':
				        newOrderLine.has_valance = true;
				        newOrderLine.has_shade = false;
				        break;
				    case 'both':
				        newOrderLine.has_valance = true;
				        newOrderLine.has_shade = true;
				        break;
				    default:
				        newOrderLine.has_shade = true;
				}

				newOrderLine.line_number = vm.order.order_lines.length + 1;
				newOrderLine.scrollTo = true;

				vm.order.order_lines.push(newOrderLine);

				$timeout(function(){
					newOrderLine.optionsApi.loadOptions();
				});
			}
		};
		vm.save = function(order){
			saveOrder(order).then(saveOrderSuccess);
			function saveOrderSuccess(){
				$window.location.href = '/dashboard';
			};
		};
	}
	function getOrder(orderId){

		return $q(getOrderPromise);

		function getOrderPromise(getOrderResolve, getOrderReject){
			bestlineApi.order(orderId).get().then(getOrderSuccess, getOrderError);

			function getOrderSuccess(response){

				vm.order = response.data;

				$timeout(function(){
					angular.forEach(vm.order.order_lines, function(orderLine, index){

						orderLine.optionsApi.loadOptions().then(calculateOrderLine);
						function calculateOrderLine(){
							orderLine.calculate();
						}
					});
				});

				getOrderResolve(response);
			};
			function getOrderError(response){
				getOrderReject(response);
			};
		};
	}
	function getNewOrderLine(orderId){

		vm.gettingNewOrderLine = true;

		return $q(getNewOrderLinePromise);
		function getNewOrderLinePromise(getNewOrderLineResolve, getNewOrderLineReject){
			bestlineApi.order(orderId).orderLine().getNew().then(getNewOrderLineSuccess, getNewOrderLineError);

			function getNewOrderLineSuccess(response){
				vm.gettingNewOrderLine = false;
				getNewOrderLineResolve(response.data);
			}
			function getNewOrderLineError(response){
				vm.gettingNewOrderLine = false;
				getNewOrderLineReject(response);
			}
		};
	}
	function setupSelectOptions(){

		vm.discountSelectOptions = [
			{value: 0, text: 'No Discount'},
			{value: 10, text: '10%'},
			{value: 20, text: '20%'},
		];
		vm.rushSelectOptions = [
			{value: 0, text: 'No Rush Charge'},
			{value: 10, text: '10%'},
			{value: 20, text: '20%'},
		];

		getSelectOptions();
	}
	function getSelectOptions(){

		return $q(getSelectOptionsPromise);

		function getSelectOptionsPromise(resolveGetSelectOptions, rejectGetSelectOptions){

			vm.loadingSelectOptions = true;
			bestlineApi.order().selectOptions().then(getSelectOptionsSuccess, getSelectOptionsError);

			function getSelectOptionsSuccess(response){

				vm.loadingSelectOptions = false;
				vm.selectOptions = response.data;
				resolveGetSelectOptions(response.data);
			};
			function getSelectOptionsError(response){
				vm.loadingSelectOptions = false;
				rejectGetSelectOptions(response);
			};
		};
	}
	function setupTotalValues(){

		vm.subtotal = function(){

			var subtotal = 0;

			if(vm.order && vm.order.order_lines && vm.order.order_lines.length > 0){

				angular.forEach(vm.order.order_lines, eachOrderLine);
				vm.order.subtotal = subtotal.toFixed(2);
			}

			function eachOrderLine(orderLine, index){
				subtotal = subtotal + formatNumber(orderLine.total_price);
			};

			return subtotal.toFixed(2);
		};

		function formatNumber(value){

			value = parseFloat(Number(value));
			return value;
		};

		vm.discount = function(){

			var discountAmount = 0;

			if(vm.order){

				discountAmount = (vm.order.subtotal * (vm.order.discount_percent / 100)).toFixed(2)
				vm.order.discount_total = discountAmount;
			}

			return discountAmount;
		};
		vm.rush = function(){
			var rushAmount = 0;

			if(vm.order){

				rushAmount = (vm.order.subtotal * (vm.order.rush_percent / 100)).toFixed(2)
				vm.order.rush_total = rushAmount;
			}

			return rushAmount;
		};
		vm.total = function(){

			var total = 0;

			if(vm.order){

				total = (formatNumber(vm.order.subtotal) - formatNumber(vm.order.deposit_amount) - formatNumber(vm.order.discount_total) + formatNumber(vm.order.rush_total) + formatNumber(vm.order.boxing_cost) + formatNumber(vm.order.shipping_amount));
				vm.order.total = total;
			}

			return total;
		};
	}
	function saveOrder(order){

		return $q(saveOrderPromise);

		function saveOrderPromise(saveOrderResolve, saveOrderReject){

			vm.savingOrder = true;
			vm.order.apiError = undefined;
			bestlineApi.order(order.id).update(order).then(saveOrderSuccess, saveOrderError);

			function saveOrderSuccess(response){

				saveOrderResolve(response);
			};
			function saveOrderError(response){

				vm.order.apiError = response.data.message;
				vm.savingOrder = undefined;
				saveOrderReject(response);
			};
		};
	}
	function confirmNavigationIfFormIsDirty(){

		$window.onbeforeunload = function(event){

			if(!vm.step2Form.$pristine && !vm.savingOrder){
				return false;
			}
		}

		$scope.$on('$destroy', function() {
		    delete $window.onbeforeunload;
		});
	}
	setup();
}

}(window.jQuery || window.$, window.angular));
