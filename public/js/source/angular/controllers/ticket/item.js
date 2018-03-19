(function ($, angular) {
'use strict';

/**
 * @ngdoc function
 * @name bestline.controller:TicketItemController
 * @description
 */
angular.module('bestline').requires.push('BestlineSvgPanZoom'); //inject BestlineSvgPanZoom into this view
angular.module('bestline')
.controller('TicketItemController', function ($scope, $timeout) {

	var TicketItemController = {}; //private object
	TicketItemController.setup = function(){

		$scope.shade = {};
		$scope.panZoom = {}; //set by directive bestline-shade-drawing
		
		TicketItemController.viewApi();
		TicketItemController.watchForDrawingResize();
	};
	TicketItemController.viewApi = function(){

		$scope.toNumber = function(string){
			return Number(string);
		};
	};
	TicketItemController.watchForDrawingResize = function(){

		var watchForDrawingResize = $scope.$on('resizeDrawing', function(event, itemId) {

	        if(itemId === 'item'+$scope.index || itemId === 'all'){
	        	$scope.panZoom.resize();
	        	$scope.panZoom.fit();
	        	$scope.panZoom.center();
	        }
	    });

		$scope.$on('$destroy', function() {
	        watchForDrawingResize();
	    });
	};
	TicketItemController.setup();
});

}(window.jQuery || window.$, window.angular));
