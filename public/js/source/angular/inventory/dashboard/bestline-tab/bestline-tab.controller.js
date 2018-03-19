(function ($, angular) {
'use strict';

/**
 * @ngdoc function
 * @name bestline.controller:bestlineTabCtrl
 * @description
 */
angular.module('bestline').controller('bestlineTabCtrl', bestlineTabCtrl);

function bestlineTabCtrl(
	$scope,
	$q,
	bestlineApi
){
	var vm = this;

	function setup(){

		vm.loading = false;
		vm.allFabrics = [];
		vm.fabricTableOptions = {};

		setFabricTableOptions();
		getBestlineFabrics();
		viewApi();
	};
	function viewApi(){};
	function getBestlineFabrics(somethingId){

		vm.loading = true;
		vm.apiErrors = [];

		return $q(getBestlineFabricsPromise);

		function getBestlineFabricsPromise(getBestlineFabricsResolve, getBestlineFabricsReject){
			bestlineApi.fabric().bestline().then(getBestlineFabricsSuccess, getBestlineFabricsError);

			function getBestlineFabricsSuccess(response){
				vm.allFabrics = response.data;
				vm.loading = false;
				getBestlineFabricsResolve(response);
			};
			function getBestlineFabricsError(response){
				vm.loading = false;
				vm.apiErrors.push(response.data.error.message);
				getBestlineFabricsReject(response);
			};
		};
	}
	function setFabricTableOptions(){
		vm.fabricTableOptions.showColumns = {
			company: false,
			sidemark: false,
			com: false,
		}
	}
	setup();
}

bestlineTabCtrl.$inject = [
  '$scope',
  '$q',
  'bestlineApi'
];

}(window.jQuery || window.$, window.angular));
