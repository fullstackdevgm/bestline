(function ($, angular) {
'use strict';

angular.module('bestline').requires.push('smart-table'); //inject ngTable into this view
/**
 * @ngdoc function
 * @name bestline.controller:DashboardController
 * @description
 */
angular.module('bestline').controller('DashboardController', DashboardController);

function DashboardController(
	$scope,
	$window,
	$timeout,
	$location,
	$q,
	filterFilter,
	bestlineApi
){
	var vm = this;

	function setup(){

		vm.loadingOrders = true;
		vm.loadingFilter = false;
		vm.orders = [];
		vm.filterApi = {}; //set by the st-api after linked
		vm.counts = {};
		vm.itemsPerPage = 50;
		vm.pageNavCount = 4;
		vm.searchParams = $location.search();
		vm.searchTerm = vm.searchParams.q;
		vm.activeTab = (vm.searchParams.filter)? vm.searchParams.filter : 'home';

		getOrders().then(getOrdersSuccess);
		function getOrdersSuccess(){
			vm.filterApi.search(vm.searchTerm, '');
			vm.filterTable(vm.activeTab, true);
		}
		viewApi();
		syncSearch();
	};
	function viewApi(){

		vm.deleteOrder = function(order){
			deleteOrder(order);
		}
		vm.orderLinkClick = function(order){
			if(!order.finalized){
			    $window.location.href = "/order/" + order.id + "/edit";
			} else if(order.finalized) {
			    $window.location.href = "/order/final/view?order=" + order.id;
			}
		}
		vm.filterTable = function(slug, keepSearch){
			vm.loadingFilter = true;
			vm.activeTab = slug;
			$location.search('filter', slug);

			$timeout(afterLoadingUpdated, 0);
			function afterLoadingUpdated(){

				if(!keepSearch){
					vm.filterApi.tableState().search = {};
					$location.search('q', null);
				}
				if(slug === 'home'){
					//vm.filterApi.tableState().search = {};
					vm.filterApi.search();
				} else if(slug === 'delivery'){
					vm.filterApi.search('Delivery', 'shipping_method.name');
				} else if(slug === 'pickups'){
					vm.filterApi.search('Pickup', 'shipping_method.name');
				} else if(slug === 'ships'){
					vm.filterApi.search('true', 'is_ship');
				} else if(slug === 'rush'){
					vm.filterApi.search(1, 'is_rush');
				} else if(slug === 'unfinalized'){
					vm.filterApi.search('false', 'is_finalized');
				}

				vm.loadingFilter = false;
			}
		}
		vm.finalize = function(order){
			finalizeOrder(order).then(finalizeOrderSuccess);
			function finalizeOrderSuccess(finalizedData){
				order.finalized = finalizedData;
			}
		}
		vm.unfinalize = function(order){
			unfinalizeOrder(order).then(unfinalizeOrderSuccess);
			function unfinalizeOrderSuccess(response){
				order.finalized = null;
			}
		}
		vm.alertsBlockingFinalization = function(order){
			return filterFilter(order.alerts, {blocks_finalization: 1}, true);
		}
		vm.confirm = function(order){
			changeFromQuoteToOrder(order);
		}
	}
	function syncSearch(){

	    var watchSearchTerm = $scope.$watch('vmDashboard.searchTerm', function(term) {

	       	$location.search('q', term);
	    });

	    $scope.$on('$destroy', watchSearchTerm);
	}
	function getOrders(){

		vm.loadingOrders = true;

		return $q(getOrdersPromise);
		function getOrdersPromise(getOrdersResolve, getOrdersReject){
			bestlineApi.order().all().openOrders().then(getOrdersSuccess, getOrdersError);

			function getOrdersSuccess(response){

				vm.orders = response.data;
				vm.loadingOrders = false;
				getCounts(vm.orders);
				getOrdersResolve(response);
			};
			function getOrdersError(response){
				vm.loadingOrders = false;
				getOrdersReject(response);
			};
		};
	}
	function deleteOrder(order){

		order.loading = true;

		return $q(deleteOrderPromise);

		function deleteOrderPromise(deleteOrderResolve, deleteOrderReject){
			bestlineApi.order(order.id).destroy().then(deleteOrderSuccess, deleteOrderError);

			function deleteOrderSuccess(response){
				order.loading = false;

				var index = vm.orders.indexOf(order);
				if (index > -1) {
					vm.orders.splice(index, 1);
				}

				deleteOrderResolve(response);
			};
			function deleteOrderError(response){
				order.loading = false;
				deleteOrderReject(response);
			};
		};
	}
	function getCounts(orders){

		vm.counts = {
			rush: 0,
			ships: 0,
			delivery: 0,
			pickups: 0,
			unfinalized: 0,
		}

		angular.forEach(orders, eachOrder);
		function eachOrder(order){
			if(order.is_rush){
				vm.counts.rush++;
			}
			if(order.is_ship){
				vm.counts.ships++;
			}
			if(order.shipping_method && order.shipping_method.name === 'Delivery'){
				vm.counts.delivery++;
			}
			if(order.shipping_method && order.shipping_method.name === 'Pickup'){
				vm.counts.pickups++;
			}
			if(!order.is_finalized){
				vm.counts.unfinalized++;
			}
		}
	}
	function finalizeOrder(order){

		order.loading = true;
		order.apiError = null;

		return $q(finalizeOrderPromise);

		function finalizeOrderPromise(finalizeOrderResolve, finalizeOrderReject){
			bestlineApi.order(order.id).finalize().then(finalizeOrderSuccess, finalizeOrderError);

			function finalizeOrderSuccess(response){
				order.loading = false;

				finalizeOrderResolve(response.data.finalized);
			};
			function finalizeOrderError(response){

				order.loading = false;
				order.apiError = response.data.error.message;
				finalizeOrderReject(response);
			};
		};
	}
	function unfinalizeOrder(order){

		order.loading = true;
		order.apiError = null;

		return $q(unfinalizeOrderPromise);

		function unfinalizeOrderPromise(unfinalizeOrderResolve, unfinalizeOrderReject){
			bestlineApi.order(order.id).finalized().unfinalize().then(unfinalizeOrderSuccess, unfinalizeOrderError);

			function unfinalizeOrderSuccess(response){
				order.loading = false;

				unfinalizeOrderResolve(response);
			};
			function unfinalizeOrderError(response){

				order.loading = false;
				order.apiError = response.data.error.message;
				unfinalizeOrderReject(response);
			};
		};
	}
	function changeFromQuoteToOrder(order){

		return $q(changeFromQuoteToOrderPromise);
		function changeFromQuoteToOrderPromise(changeFromQuoteToOrderResolve, changeFromQuoteToOrderReject){

			order.loading = true;
			order.apiError = null;
			bestlineApi.order(order.id).confirm().then(changeFromQuoteToOrderSuccess, changeFromQuoteToOrderError);

			function changeFromQuoteToOrderSuccess(response){
				order.loading = false;
				order.is_quote = 0;
			};
			function changeFromQuoteToOrderError(response){
				order.loading = false;
				order.apiError = response.data.error.message;
				changeFromQuoteToOrderReject(response);
			};
		};
	}
	setup();
}

DashboardController.$inject = [
  '$scope',
  '$window',
  '$timeout',
  '$location',
  '$q',
  'filterFilter',
  'bestlineApi'
];

}(window.jQuery || window.$, window.angular));
