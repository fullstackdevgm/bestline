(function ($, angular) {'use strict';
/**
 * @ngdoc function
 * @name bestline.controller:PartsOptionsController
 * @description
 */
angular.module('bestline').controller('PartsOptionsController', PartsOptionsController);

function PartsOptionsController(
	$scope,
	$q,
	bestlineApi,
	$uibModal
){
	var vm = this;

	function setup(){

		vm.options = [];
		vm.selectedOption = null;
		vm.activeTab = 'suboptions';

		viewApi();
		getAllOptions();
		getAllSuboptions();
	}
	function viewApi(){}
	function getAllOptions(){

		return $q(getAllOptionsPromise);

		function getAllOptionsPromise(getAllOptionsResolve, getAllOptionsReject){

			vm.loadingOptions = true;

			bestlineApi.parts().options().all().then(getAllOptionsSuccess, getAllOptionsError);

			function getAllOptionsSuccess(response){
				vm.options = response.data;
				vm.loadingOptions = false;
				getAllOptionsResolve(response);
			}
			function getAllOptionsError(response){
				vm.loadingOptions = false;
				getAllOptionsReject(response);
			}
		}
	}
	function getAllSuboptions(){

		return $q(getAllSuboptionsPromise);

		function getAllSuboptionsPromise(getAllSuboptionsResolve, getAllSuboptionsReject){

			vm.loadingSuboptions = true;

			bestlineApi.parts().options().allSuboptions().then(getAllSuboptionsSuccess, getAllSuboptionsError);

			function getAllSuboptionsSuccess(response){
				vm.suboptions = response.data;
				vm.loadingSuboptions = false;
				getAllSuboptionsResolve(response);
			}
			function getAllSuboptionsError(response){
				vm.loadingSuboptions = false;
				getAllSuboptionsReject(response);
			}
		}
	}
	setup();
}
PartsOptionsController.$inject = [
  '$scope',
  '$q',
  'bestlineApi',
  '$uibModal'
];

}(window.jQuery || window.$, window.angular));
