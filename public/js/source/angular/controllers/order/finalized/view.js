(function ($, angular) {
'use strict';

/**
 * @ngdoc function
 * @name bestline.controller:FinalizedViewController
 * @description
 */
angular.module('bestline').controller('FinalizedViewController', FinalizedViewController);

function FinalizedViewController($scope, $q, $location, bestlineApi){

	var vm = this;

	function setup(){

		vm.loadingOrder = false;
		vm.searchParams = $location.search();

		//set scope variables here
		getOrder(vm.searchParams.order).then(function(response){
			vm.order = response;
		});
		viewApi();
	};
	function viewApi(){

		vm.getDate = function(mysqlDateTime){
			var dateParts = mysqlDateTime.split(/[- :]/);
			return new Date(Date.UTC(dateParts[0], dateParts[1]-1, dateParts[2], dateParts[3], dateParts[4], dateParts[5]));
		}
	};
	function getOrder(orderId){

		return $q(getOrderPromise);

		function getOrderPromise(getOrderResolve, getOrderReject){

			vm.loadingOrder = true;
			bestlineApi.order(orderId).get().then(getOrderSuccess, getOrderError);

			function getOrderSuccess(response){

				vm.loadingOrder = false;
				getOrderResolve(response.data);
			};
			function getOrderError(response){
				vm.loadingOrder = false;
				getOrderReject(response);
			};
		};
	};
	setup();
}

}(window.jQuery || window.$, window.angular));
