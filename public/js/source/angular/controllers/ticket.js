(function ($, angular) {
'use strict';

/**
 * @ngdoc function
 * @name bestline.controller:TicketController
 * @description
 */
angular.module('bestline')
.controller('TicketController', function ($scope, $rootScope, $window, $location, $timeout, $filter, bestlineApi) {

	var TicketController = {}; //private object
	TicketController.setup = function(){

		$scope.searchParams = $location.search();
		$scope.finalizedOrder = undefined;
		$scope.activeTab = null; //used by view to set active tab
		$scope.showSidetabs = false;
		$scope.showTemporaryPrintStyles = false;

		//set scope variables here
		TicketController.checkActiveTab();
		TicketController.viewApi();
		TicketController.getOrder($scope.searchParams.order, TicketController.orderCallback);
	};
	TicketController.viewApi = function(){

		$scope.setActiveTab = function(name){

			$scope.activeTab = name;
			$timeout(function(){
				$scope.$broadcast('resizeDrawing', name);
			});
		};
		$scope.printPink = function(){
			$scope.showPinkPrintStyles = true;

			$timeout(function(){

				$window.print();
				$scope.showPinkPrintStyles = false;
			}, 200);
		};
		$scope.print = function(){

			//toggle temporary print styles to prepare the drawings for print
			$scope.showTemporaryPrintStyles = ($scope.showTemporaryPrintStyles)? false: true;


			if($scope.showTemporaryPrintStyles){
				//wait till temporary styles are applied
				$timeout(function(){

					//broadcast to all drawings to resize
					$scope.$broadcast('resizeDrawing', 'all');
					
					$timeout(function(){

						$window.print();
						$scope.showTemporaryPrintStyles = false;
						$timeout(function(){
							//broadcast to the active tab to resize
							$scope.$broadcast('resizeDrawing', $scope.activeTab);
						});
					}, 200);
				});
			}
		};
		$scope.getDate = function(date){

			if(date){

				var dayDate = date.date.split(' ')[0];
				return $filter('date')(dayDate, 'MM-dd-yyyy');
			}
		};
		$scope.hasEmbellishmentFabric = function(order){

			if(!order){return false;}

			var hasEmbellishment = false;
			angular.forEach(order.fabrics, eachFabric);
			function eachFabric(fabric, index){

				if(fabric.type.type === 'embellishment'){
					hasEmbellishment = true;
				}
			};

			return hasEmbellishment;
		};
	};
	TicketController.getOrder = function(orderId, successCallback){

		var getOrder = {};
		
		getOrder.setup = function(){
	
			bestlineApi.order(orderId).finalized().get().then(getOrder.success, getOrder.failure);
		};
		getOrder.success = function(response){

			if(successCallback){
				successCallback(response.data);
			}
			getOrder.finish();
		};
		getOrder.failure = function(response){
	
			getOrder.finish();
		};
		getOrder.finish = function(){
			//clean up
		};
		getOrder.setup();
	};
	TicketController.orderCallback = function(finalizedOrder){

		$scope.finalizedOrder = finalizedOrder;
	};
	TicketController.checkActiveTab = function(searchParams){

		var hash = $location.hash();

		if(hash){
			$scope.activeTab = hash;
		} else {
			$scope.activeTab = 'pink';
		}
	};
	TicketController.setup();
});
}(window.jQuery || window.$, window.angular));
