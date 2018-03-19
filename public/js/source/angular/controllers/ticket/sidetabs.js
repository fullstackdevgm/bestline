(function ($, angular) {
'use strict';

/**
 * @ngdoc function
 * @name bestline.controller:SideTabsController
 * @description
 */
angular.module('bestline')
.controller('SideTabsController', function ($scope) {

	var dbugThis = false; var dbugAll = false;
	if(dbugAll||dbugThis){console.log("%ccalled SideTabsController()","color:orange");}

	var vm = this;
	var SideTabsController = {}; //private object
	SideTabsController.setup = function(){

		vm.measurements = [];

		SideTabsController.setupDrawing();
		SideTabsController.watchForDrawingResize();
		SideTabsController.viewApi();
	};
	SideTabsController.setupDrawing = function(){

		//var dbugThis = true;
		if(dbugAll||dbugThis){console.log("%ccalled SideTabsController.setupDrawing()","color:orange");}
	
		var spacing = .3;
		var lineWidth = 0.045;
		var fontSize = 0.4;

		//setup drawing layout values
		vm.drawing = {
			width: undefined,
			itemsCnt: 0,
			fontSize: fontSize,
		}
		vm.drawing.titleRow = {
			height: spacing * 4,
			y: 0,
		};
		vm.drawing.measurementRow = {
			height: spacing,
			y: vm.drawing.titleRow.height + vm.drawing.titleRow.y,
		}
		vm.drawing.drawingsRow = {
			height: 0,
			y: vm.drawing.measurementRow.height + vm.drawing.measurementRow.y,
			measurementCol: {
				width: 0.7,
				marginRight: 1.5,
			}
		};

		//setup sidetabs
		var drawingColX = 0;
		angular.forEach($scope.finalizedOrder.sidetabs, setupSidetabs);
		function setupSidetabs(sidetab, index){

			//add measurements
			vm.measurements.push({height: sidetab.height, x: drawingColX, y: vm.drawing.drawingsRow.y, lineWidth: lineWidth, width: 0.5, fontSize: fontSize,});
			vm.measurements.push({height: sidetab.width, x: drawingColX + vm.drawing.drawingsRow.measurementCol.width,y: vm.drawing.measurementRow.y, rotate:true, flip: true, lineWidth: lineWidth, width: 0.5, fontSize: fontSize,});

			//modify existing sidetab
			sidetab.y = vm.drawing.drawingsRow.y;
			sidetab.x = drawingColX + vm.drawing.drawingsRow.measurementCol.width;
			sidetab.lineWidth = lineWidth;

			//change x for next item
			drawingColX = drawingColX + sidetab.width + vm.drawing.drawingsRow.measurementCol.marginRight;
			
			//set drawingRow height to max of sidetab.height
			if(vm.drawing.drawingsRow.height < sidetab.height){
				vm.drawing.drawingsRow.height = sidetab.height;
			}

			//increment items count
			vm.drawing.itemsCnt = vm.drawing.itemsCnt + sidetab.items.length;
		}

		vm.drawing.width = drawingColX - vm.drawing.drawingsRow.measurementCol.marginRight;
		vm.drawing.height = vm.drawing.drawingsRow.y + vm.drawing.drawingsRow.height;
	};
	SideTabsController.watchForDrawingResize = function(){

		//var dbugThis = true;
		if(dbugAll||dbugThis){console.log("%ccalled SideTabsController.watchForDrawingResize()","color:orange");}

		var watchForDrawingResize = $scope.$on('resizeDrawing', function(event, itemId) {
	        if(itemId === 'side-tabs' || itemId === 'all'){
	        	vm.panZoom.resize();
	        	vm.panZoom.fit();
	        	vm.panZoom.center();
	        }
	    });

		$scope.$on('$destroy', function() {
	        watchForDrawingResize();
	    });
	};
	SideTabsController.viewApi = function(){

		//functions to be used in view can be added to $scope here
	};
	SideTabsController.setup();
});
}(window.jQuery || window.$, window.angular));
