(function ($, angular) {
'use strict';

/**
 * @ngdoc function
 * @name bestline.controller:unknownFabricTabController
 * @description
 */
angular.module('bestline').controller('unknownFabricTabController', unknownFabricTabController);

function unknownFabricTabController(
	$scope,
	$q,
	bestlineApi
){
	var vm = this;

	function setup(){

		vm.unknownFabrics = [];
		vm.fabricTableOptions = {};

		setFabricTableOptions();
		getUnknownFabrics();
		viewApi();
	};
	function viewApi(){

		//functions to be used in view can be added to $scope here
	};
	function getUnknownFabrics(somethingId){

		vm.apiErrors = [];

		return $q(getUnknownFabricsPromise);

		function getUnknownFabricsPromise(getUnknownFabricsResolve, getUnknownFabricsReject){
			bestlineApi.fabric().unknown().then(getUnknownFabricsSuccess, getUnknownFabricsError);

			function getUnknownFabricsSuccess(response){
				vm.unknownFabrics = response.data;
				getUnknownFabricsResolve(response);
			};
			function getUnknownFabricsError(response){
				vm.apiErrors.push(response.data.error.message);
				getUnknownFabricsReject(response);
			};
		};
	}
	function setFabricTableOptions(){
		vm.fabricTableOptions.showColumns = {
			company: false,
			com: false,
		}
	}
	setup();
}

unknownFabricTabController.$inject = [
  '$scope',
  '$q',
  'bestlineApi'
];

}(window.jQuery || window.$, window.angular));
