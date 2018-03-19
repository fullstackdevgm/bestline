(function ($, angular) {'use strict';
	/**
	 * @ngdoc function
	 * @name bestline.controller:PartsProductEditController
	 * @description
	 */
	angular.module('bestline').controller('PartsProductEditController', PartsProductEditController);

	function PartsProductEditController(
		$scope,
		$q,
		$location,
		$window,
		bestlineApi
	){
		var vm = this;

		function setup(){

			var urlProductIdString = $location.path().split("/")[3];
			var urlProductId = Number(urlProductIdString);
			var productId = isNaN(urlProductId)? null : urlProductId;

			vm.product = null;
			vm.apiErrors = [];

			viewApi();
			onEvents();
			if(urlProductIdString === 'new'){
				vm.product = {isNew: true};
			} else if(productId){
				getProduct(productId);
			}
		}
		function viewApi(){
			//functions to be used in view can be added to $scope here
		}
		function onEvents(){

			var onProductEditComplete = $scope.$on('product-edit-complete', function(e, savedProduct){
				redirectBackToProductsPage();
			});
			var onProductEditCancel = $scope.$on('product-edit-canceled', function(e, product){
				redirectBackToProductsPage();
			});
			var onProductDelete = $scope.$on('product-deleted', function(e, product){
				redirectBackToProductsPage();
			});

			$scope.$on('$destroy', onDestroy);
			function onDestroy(){
				onProductEditComplete();
				onProductEditCancel();
				onProductDelete();
			}
		}
		function redirectBackToProductsPage(){
			$window.location.href = "/parts/products";
		}
		function getProduct(productId){

			return $q(getProductPromise);

			function getProductPromise(getProductResolve, getProductReject){

				vm.apiErrors = [];
				bestlineApi.parts().products().getProduct(productId).then(getProductSuccess, getProductError);

				function getProductSuccess(response){
					vm.product = response.data;
					getProductResolve(response);
				}
				function getProductError(response){
					vm.apiErrors.push(response.data.error.message);
					getProductReject(response);
				}
			}
		}
		setup();
	}
	PartsProductEditController.$inject = [
	  '$scope',
	  '$q',
	  '$location',
	  '$window',
	  'bestlineApi'
	];
}(window.jQuery || window.$, window.angular));
