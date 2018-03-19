(function ($, angular) {
'use strict';

/**
 * @ngdoc function
 * @name bestline.controller:HeaderboardRodTicketController
 * @description
 */
angular.module('bestline').controller('HeaderboardRodTicketController', HeaderboardRodTicketController);

function HeaderboardRodTicketController($scope){

	var vm = this;

	function setup(){

		viewApi();
	};
	function viewApi(){

		//functions to be used in view can be added to $scope here
		vm.replaceBlackoutThermalWithStock = function(fabricName){
			if(fabricName === 'Blackout' || fabricName === 'Thermal'){
				return 'Stock';
			}

			return fabricName;
		}
	};
	setup();
}

}(window.jQuery || window.$, window.angular));
