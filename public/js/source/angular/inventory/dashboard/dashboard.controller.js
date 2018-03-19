(function ($, angular) {'use strict';
/**
 * @ngdoc function
 * @name bestline.controller:InventoryDashboardCtrl
 * @description
 */
angular.module('bestline').controller('InventoryDashboardCtrl', InventoryDashboardCtrl);

function InventoryDashboardCtrl(
	$scope,
	$q,
	$location
){
	var vm = this;

	function setup(){
		checkActiveTab();
		viewApi();
	};
	function viewApi(){
		//functions to be used in view can be added to $scope here
	};
	function checkActiveTab(searchParams){

		var hash = $location.hash();

		if(hash){
			vm.activeTab = hash;
		} else {
			vm.activeTab = 'all';
		}
	};
	setup();
}
InventoryDashboardCtrl.$inject = [
  '$scope',
  '$q',
  '$location'
];

}(window.jQuery || window.$, window.angular));
