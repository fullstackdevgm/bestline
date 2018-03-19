(function ($, angular) {
'use strict';

/**
 * @ngdoc function
 * @name bestline.controller:PinkTicketController
 * @description
 */
angular.module('bestline').controller('PinkTicketController', PinkTicketController);

function PinkTicketController($scope){

	var vm = this;

	var PinkTicketController = {}; //private object
	PinkTicketController.setup = function(){

		//set scope variables here

		PinkTicketController.viewApi();
	};
	PinkTicketController.viewApi = function(){

		vm.isUnlined = function(order){

			var isUnlined = true;

			if(!order){
				return false;
			}

			angular.forEach(order.fabrics, eachFabric);
			function eachFabric(fabric, index){

				if(fabric.type.type === 'lining'){
					isUnlined = false;
				}
			}

			return isUnlined;
		};
	};
	PinkTicketController.setup();
};
}(window.jQuery || window.$, window.angular));
