(function ($, angular) {'use strict';
/**
 * @ngdoc function
 * @name bestline.controller:PartsProductsController
 * @description
 */
angular.module('bestline').controller('PartsProductsController', PartsProductsController);

function PartsProductsController(
	$scope,
	$q,
	bestlineApi,
	$uibModal
){
	var vm = this;

	function setup(){

		vm.products = [];

		viewApi();
		getAllProducts();
	}
	function viewApi(){}
	function getAllProducts(){

		return $q(getAllProductsPromise);

		function getAllProductsPromise(getAllProductsResolve, getAllProductsReject){
			bestlineApi.parts().products().all().then(getAllProductsSuccess, getAllProductsError);

			function getAllProductsSuccess(response){
				vm.products = response.data;
				getAllProductsResolve(response);
			}
			function getAllProductsError(response){
				getAllProductsReject(response);
			}
		}
	}
	setup();
}
PartsProductsController.$inject = [
  '$scope',
  '$q',
  'bestlineApi',
  '$uibModal'
];

}(window.jQuery || window.$, window.angular));
