(function ($, angular) { 'use strict';

/**
 * @ngdoc function
 * @name bestline.controller:BestlineOrderLinesCheckout
 * @description
 */
angular.module('bestline').controller('BestlineOrderLinesCheckout', BestlineOrderLinesCheckout);
function BestlineOrderLinesCheckout($q, $uibModalInstance, bestlineApi, orderLines, users){

	var vm = this;

	function setup(){

		vm.orderLines = orderLines;
		vm.users = users;

		viewApi();
	}
	function viewApi(){
		vm.checkout = function(user){

			var promises = [];
			vm.apiErrors = [];
			vm.loading = true;

			angular.forEach(vm.orderLines, eachOrderLine);
			function eachOrderLine(orderLine){
				promises.push(checkoutOrderLine(orderLine, user.id));
			}

			$q.all(promises).then(allOrderLinesCheckoutSuccess, allOrderLinesCheckoutError);
			function allOrderLinesCheckoutSuccess(response){
				vm.loading = false;
				$uibModalInstance.close();
			}
			function allOrderLinesCheckoutError(response){
				vm.loading = false;
				$uibModalInstance.dismiss('cancel');
			}
		};
		vm.cancel = function(){
			$uibModalInstance.dismiss('cancel');
		}
	}
	function checkoutOrderLine(orderLine, userId){

		orderLine.loading = true;
		orderLine.apiErrors = [];

		return $q(checkoutOrderLinePromise);
		function checkoutOrderLinePromise(checkoutOrderLineResolve, checkoutOrderLineReject){

			bestlineApi.order('any').orderLine(orderLine.id).work('new').checkout(userId).then(checkoutOrderLineSuccess, checkoutOrderLineError);
			function checkoutOrderLineSuccess(response){

				orderLine.current_work = response.data;
				orderLine.loading = false;
				checkoutOrderLineResolve(response.data);
			};
			function checkoutOrderLineError(response){
				orderLine.loading = false;
				orderLine.apiErrors.push(response.data.error.message)
				checkoutOrderLineReject(response.data);
			};
		};
	}
	setup();
}

}(window.jQuery || window.$, window.angular));
