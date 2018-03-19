(function ($, angular) {
'use strict';

/**
 * @ngdoc function
 * @name bestline.controller:OrderLineController
 * @description
 */
angular.module('bestline').controller('OrderLineController', OrderLineController);

function OrderLineController($scope, $q, $timeout, filterFilter, bestlineApi){

	var vm = this;

	function setup(){

		vm.optionsSettings = {
			type: 'line',
		}
		vm.apiErrors = [];

		vm.lineInit = function(order, orderLine, selectOptions){
			vm.orderLine = orderLine;
			setSavedPrices(orderLine);
			vm.orderLine.calculate = function(){
				calculateOrderLine(this);
			};
			vm.order = order;
			vm.selectOptions = selectOptions;
		}

		viewApi();
		onEvents();
	};
	function viewApi(){

		vm.calculate = function(orderLine){
			calculateOrderLine(orderLine);
		};
		vm.delete = function(orderLine){

			var hasId = typeof orderLine.id !== 'undefined';

			if(hasId){
				deleteOrderLine(orderLine).then(removeFromView)
			} else {
				removeFromView();
			}

			function removeFromView(){
			    var index = vm.order.order_lines.indexOf(orderLine);
			    vm.order.order_lines.splice(index, 1);

				var lineNumber = orderLine.line_number;
				angular.forEach(vm.order.order_lines, eachOrderLine);
				function eachOrderLine(orderLine, orderLineIndex){
					if(orderLine.line_number > lineNumber){
						orderLine.line_number = orderLine.line_number - 1;
					}
				};
			};
		};
		vm.addOption = function(orderLine){

			var newOption = {};

			if(orderLine.id){
				newOption.order_line_id = orderLine.id;
			}

			orderLine.optionsApi.newOption(newOption);
		};
		vm.filterByHardwareId = function(pullType, pullTypeIndex, allPullTypes){

			var foundMatch = false;

			angular.forEach(pullType.hardware, eachHardware);
			function eachHardware(hardware, hardwareIndex){
				if(hardware.id === vm.orderLine.hardware_id){

					foundMatch = true;
				}
			};

			return foundMatch;
		};
		vm.onPullTypeChange = function(orderLine){

			var pullTypeObject = filterFilter(vm.selectOptions.pull_types, {id: orderLine.pull_type_id}, true)[0];

			if(pullTypeObject && pullTypeObject.related_option_id){

				var orderLineHasOption = filterFilter(orderLine.options, {option_id: pullTypeObject.related_option_id}, true)[0];

				if(!orderLineHasOption){

					vm.orderLine.optionsApi.newOption({
						option_id: pullTypeObject.related_option_id,
					});
				}
			}
		};
		vm.onHardwareChange = function(hardwareId, orderLine){

			var hardware = filterFilter(vm.selectOptions.hardware, {id: hardwareId}, true)[0];
			if(hardware.related_option_id){
				orderLine.optionsApi.newOption({
					option_id: hardware.related_option_id
				});
			}
		}
		vm.removeShade = function(orderLine) {
			orderLine.has_shade = false;
			orderLine.width = null;
			orderLine.height = null;
			orderLine.return = 0;
			orderLine.headerboard = null;
			orderLine.cord_length = 0;
		};
		vm.removeValance = function(orderLine){
			orderLine.has_valance = false;
			orderLine.valance_height = null;
			orderLine.valance_width = null;
			orderLine.valance_headerboard = null;
			orderLine.valance_return = null;
		};
		vm.addValance = function(orderLine) {
			orderLine.has_valance = true;

			setProperValanceTypeId(orderLine);
			$scope.orderLineForm.$setDirty();
		};
		vm.valanceHasAttachementOption = function(orderLine){
			var hasAttachement = false;

			angular.forEach(orderLine.options, eachOption);
			function eachOption(orderLineOption, index, options){

				if(orderLineOption.option && orderLineOption.option.name === 'Valance Application'){
					hasAttachement = true;
					orderLine.valanceAttachmentOption = orderLineOption;
				}
			}

			return hasAttachement;
		};
		vm.addValanceAttachedOption = function(orderLine){
			orderLine.optionsApi.newOption({
				load_option_name: 'Valance Application',
				load_sub_option_name: 'Attached Valance',
			});
			vm.calculateValanceValues(orderLine);
			setProperValanceTypeId(orderLine);
		};
		vm.addValanceCordForwardOption = function(orderLine){
			orderLine.optionsApi.newOption({
				load_option_name: 'Valance Application',
				load_sub_option_name: 'Cord Forward Valance',
			});
			vm.calculateValanceValues(orderLine);
			setProperValanceTypeId(orderLine);
		};
		vm.addValanceTdbuOption = function(orderLine){
			orderLine.optionsApi.newOption({
				load_option_name: 'Valance Application',
				load_sub_option_name: 'TDBU/BU Valance',
			});
			vm.calculateValanceValues(orderLine);
			setProperValanceTypeId(orderLine);
		};
		vm.calculateValanceValues = function(orderLine){

			if(orderLine.has_valance && orderLine.has_shade && !orderLine.valance_width && !orderLine.valance_headerboard){

				var mount = filterFilter(vm.selectOptions.mounts, {id: orderLine.mount_id}, true)[0];

				if(mount && mount.description === 'IB'){
					orderLine.valance_width = orderLine.width;
				} else if(mount && mount.description === 'OB'){
					orderLine.valance_width = Number(orderLine.width) + .75;
				}

				if(vm.valanceHasAttachementOption(orderLine)){

					if(orderLine.valanceAttachmentOption.sub_option && orderLine.valanceAttachmentOption.sub_option.name === 'Attached Valance'){
						orderLine.valance_headerboard = Number(orderLine.headerboard) + 1;
					}
				}
			}
		};
	};
	function setProperValanceTypeId(orderLine) {

		var mount = filterFilter(vm.selectOptions.mounts, {id: orderLine.mount_id}, true)[0];
		var newValanceType;
		if(mount && mount.description === 'IB'){
			newValanceType = filterFilter(vm.selectOptions.valance_types, {type: 'slug_flat'}, true)[0];
		} else if(mount && mount.description === 'OB'){
			newValanceType = filterFilter(vm.selectOptions.valance_types, {type: 'slug_box_pleated'}, true)[0];
		}
		orderLine.valance_type_id = (newValanceType)? newValanceType.id : null;
	};
	function onEvents(){

		var onOptionChange = $scope.$on('option-changed', handleOptionChange);
		var onSubOptionChanged = $scope.$on('sub-option-changed', handleOptionChange);
		var onOrderOptionDeleted = $scope.$on('order-option-deleted', handleOptionChange);
		function handleOptionChange(e, data){

			if(e.name === 'sub-option-changed'){
				setHardwareIfRelated(data);
				vm.calculateValanceValues(vm.orderLine);
			}

			calculateOrderLine(vm.orderLine);
		};

		var checkForEmbellishment = $scope.$on('option-needs-embellishment', function(e, orderOption){

			//alert if there is no embellishment fabric
			var embellishmentFabric = null;
			angular.forEach(vm.order.fabrics, checkEmbellishment);
			function checkEmbellishment(orderFabric, index){

				if(orderFabric.type && orderFabric.type.type === 'embellishment'){
					embellishmentFabric = orderFabric;
				}
			};

			if(!embellishmentFabric){
				alert('This option needs an embellishment fabric.');
				return;
			}

			//set fabric id if embellishment option is added directly to an orderline
			var index = vm.orderLine.options.indexOf(orderOption);
			if(index !== -1){
				orderOption.order_fabric_id = embellishmentFabric.id;
			}
		});

		$scope.$on('$destroy', function(){
			onOptionChange();
			onSubOptionChanged();
			onOrderOptionDeleted();
		});
	};
	function calculateOrderLine(orderLine){

		vm.apiErrors = [];

		if(vm.calculateDeferred){
			vm.calculateDeferred.reject()
		}

		orderLine.calculating = true;
		vm.calculateDeferred = $q.defer();

		bestlineApi.order(orderLine.order_id).orderLine(orderLine.id).calculate(orderLine, vm.calculateDeferred.promise).then(calculateOrderLineSuccess, calculateOrderLineError);

		function calculateOrderLineSuccess(response){
			handleCalculateSuccess(response);
			orderLine.calculating = false;
			vm.calculateDeferred.resolve(response);
		};
		function calculateOrderLineError(response){

			orderLine.calculating = false;

			if(response.status === -1){
				//a pending request was canceled by a newer one
				return false;
			}

			vm.apiErrors.push(response.data.error.message);
			vm.calculateDeferred.reject(response);
		};

		return vm.calculateDeferred.promise;
	};
	function handleCalculateSuccess(response){

		vm.orderLine.shade_price = response.data.shade_price.toFixed(2);
		vm.orderLine.valance_price = response.data.valance_price.toFixed(2);
		vm.orderLine.fabric_price = response.data.fabric_price.toFixed(2);
		vm.orderLine.options_price = response.data.options_price.toFixed(2);
		vm.orderLine.total_price = response.data.total_price.toFixed(2);

		angular.forEach(vm.orderLine.options, eachOrderLineOption);
		function eachOrderLineOption(option, index){
			var optionMatchString = 'o'+option.option_id+'so'+option.sub_option_id;
			angular.forEach(response.data.options, eachResponseOption);
			function eachResponseOption(responseOption, responseIndex){
				var responseOptionMatchString = 'o'+responseOption.option_id+'so'+responseOption.sub_option_id;
				if(optionMatchString === responseOptionMatchString){

					vm.orderLine.options[index].price = Number(responseOption.price).toFixed(2);
				}
			};
		}
	};
	function deleteOrderLine(orderLine){

		return $q(deleteOrderLinePromise);

		function deleteOrderLinePromise(deleteOrderLineResolve, deleteOrderLineReject){

			orderLine.deleting = true;
			bestlineApi.order(orderLine.order_id).orderLine(orderLine.id).destroy().then(deleteOrderLineSuccess, deleteOrderLineError);

			function deleteOrderLineSuccess(response){
				orderLine.deleting = undefined;
				deleteOrderLineResolve(response);
			};
			function deleteOrderLineError(response){
				orderLine.deleting = undefined;
				deleteOrderLineReject(response);
			};
		}
	};
	function setHardwareIfRelated(){

		var allOptionIds = [];

		angular.forEach(vm.orderLine.options, eachOrderLineOption);
		function eachOrderLineOption(option, optionIndex){
			allOptionIds.push(option.option_id);
			allOptionIds.push(option.sub_option_id);
		};

		//check to see if the IDs match the related_option for hardware
		angular.forEach(vm.selectOptions.hardware, eachHardware);
		function eachHardware(hardware, hardwareIndex){

			if(allOptionIds.indexOf(hardware.related_option_id) > -1){
				vm.orderLine.hardware_id = hardware.id;
			}
		};
	}
	function setSavedPrices(orderLine){

		orderLine.shade_price_saved = orderLine.shade_price;
		orderLine.valance_price_saved = orderLine.valance_price;
		orderLine.fabric_price_saved = orderLine.fabric_price;
		orderLine.options_price_saved = orderLine.options_price;
		orderLine.total_price_saved = orderLine.total_price;
	}
	setup();
}

}(window.jQuery || window.$, window.angular));
