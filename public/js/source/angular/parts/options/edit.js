(function ($, angular) {'use strict';
	/**
	 * @ngdoc function
	 * @name bestline.controller:PartsOptionsEditController
	 * @description
	 */
	angular.module('bestline').controller('PartsOptionsEditController', PartsOptionsEditController);

	function PartsOptionsEditController(
		$scope,
		$q,
		$location,
		$window,
		bestlineApi
	){
		var vm = this;

		function setup(){

			var urlOptionIdString = $location.path().split("/")[3];
			var urlOptionId = Number(urlOptionIdString);
			var optionId = isNaN(urlOptionId)? null : urlOptionId;
			var searchParams = $location.search();
			var parentId = (searchParams.parent)? searchParams.parent : null;

			vm.option = null;
			vm.apiErrors = [];

			viewApi();
			onEvents();
			loadData(urlOptionIdString, optionId, parentId);
		}
		function viewApi(){
			//functions to be used in view can be added to $scope here
		}
		function loadData(urlOptionIdString, optionId, parentId){

			if(urlOptionIdString === 'new'){
				if(!parentId){
					vm.option = {
						isNew: true,
					};
				} else {
					getOption(parentId).then(function(option){
						vm.option = {
							isNew: true,
							parent_id: parentId,
							parents: option,
						};
					});
				}
			} else if(optionId){
				getOption(optionId).then(function(option){
					vm.option = option;
				});
			}
		}
		function onEvents(){

			var onOptionEditComplete = $scope.$on('option-edit-complete', function(e, savedOption){
				redirectBackToPreviousPage();
			});
			var onOptionEditCancel = $scope.$on('option-edit-canceled', function(e, option){
				redirectBackToPreviousPage();
			});
			var onOptionDelete = $scope.$on('option-deleted', function(e, option){
				redirectBackToPreviousPage();
			});

			$scope.$on('$destroy', onDestroy);
			function onDestroy(){
				onOptionEditComplete();
				onOptionEditCancel();
				onOptionDelete();
			}
		}
		function redirectBackToPreviousPage(){
			$window.history.back();
		}
		function getOption(optionId){

			return $q(getOptionPromise);

			function getOptionPromise(getOptionResolve, getOptionReject){

				vm.apiErrors = [];
				bestlineApi.parts().options().getOption(optionId).then(getOptionSuccess, getOptionError);

				function getOptionSuccess(response){
					getOptionResolve(response.data);
				}
				function getOptionError(response){
					vm.apiErrors.push(response.data.error.message);
					getOptionReject(response);
				}
			}
		}
		setup();
	}
	PartsOptionsEditController.$inject = [
	  '$scope',
	  '$q',
	  '$location',
	  '$window',
	  'bestlineApi'
	];
}(window.jQuery || window.$, window.angular));
