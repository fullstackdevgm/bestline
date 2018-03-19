(function ($, angular) {
'use strict';

/**
 * @ngdoc function
 * @name bestline.controller:allTabCtrl
 * @description
 */
angular.module('bestline').controller('allTabCtrl', allTabCtrl);

function allTabCtrl(
	$scope,
	$q,
	bestlineApi
){
	var vm = this;

	function setup(){

		vm.loading = false;
		vm.allFabrics = [];

		getAllFabrics();
		viewApi();
	};
	function viewApi(){};
	function getAllFabrics(somethingId){

		vm.loading = true;
		vm.apiErrors = [];

		return $q(getAllFabricsPromise);

		function getAllFabricsPromise(getAllFabricsResolve, getAllFabricsReject){
			bestlineApi.fabric().all().then(getAllFabricsSuccess, getAllFabricsError);

			function getAllFabricsSuccess(response){
				vm.allFabrics = response.data;
				vm.loading = false;
				getAllFabricsResolve(response);
			};
			function getAllFabricsError(response){
				vm.loading = false;
				vm.apiErrors.push(response.data.error.message);
				getAllFabricsReject(response);
			};
		};
	};
	setup();
}

allTabCtrl.$inject = [
  '$scope',
  '$q',
  'bestlineApi'
];

}(window.jQuery || window.$, window.angular));
