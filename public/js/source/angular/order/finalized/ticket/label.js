(function ($, angular) {'use strict';
/**
 * @ngdoc function
 * @name bestline.controller:TicketLabelController
 * @description
 */
angular.module('bestline').controller('TicketLabelController', TicketLabelController);

function TicketLabelController(
	$scope,
	$q,
	$location,
	$timeout,
	$window,
	bestlineApi
){
	var vm = this;

	function setup(){

		var orderId = $location.search().order;
		vm.order = {};

		viewApi();
		onEvents();
		getOrderLines(orderId);
	}
	function viewApi(){
		//functions to be used in view can be added to $scope here
	}
	function onEvents(){

		var onSomething = $scope.$on('something', function(e, data){
			//use this to catch broadcasts
		});

		$scope.$on('$destroy', onSomething);
	}
	function getOrderLines(orderId){

		return $q(getOrderLinesPromise);

		function getOrderLinesPromise(getOrderLinesResolve, getOrderLinesReject){
			bestlineApi.order(orderId).finalized().labels().then(getOrderLinesSuccess, getOrderLinesError);

			function getOrderLinesSuccess(response){
				vm.order = response.data;
				$timeout($window.print);
				getOrderLinesResolve(response);
			}
			function getOrderLinesError(response){
				getOrderLinesReject(response);
			}
		}
	}
	setup();
}
TicketLabelController.$inject = [
  '$scope',
  '$q',
  '$location',
  '$timeout',
  '$window',
  'bestlineApi'
];

}(window.jQuery || window.$, window.angular));
