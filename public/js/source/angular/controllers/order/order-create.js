(function ($, angular, Order) {
'use strict';

/**
 * @ngdoc function
 * @name bestline.controller:OrderCreateController
 * @description
 */
angular.module('bestline').controller('OrderCreateController', OrderCreateController);

function OrderCreateController(
	$scope,
	$location,
	$timeout,
	$q,
	$window,
	bestlineApi,
	filterFilter
){

	var OrderCreateController = {}; //private object
	OrderCreateController.setup = function(){

		$scope.selectOptions = {};
		$scope.addresses = [];
		$scope.contacts = [];
		$scope.request = {}; //populated by view
		$scope.searchParams = $location.search();
		$scope.fabricApi = {}; //populated by directive
		$scope.loadDataFromOrder = false;
		$scope.optionsApi = {}; //populated by directive
		$scope.order = {
			copyBilling: true,
			is_quote: ($scope.searchParams.is_quote ==='true')? 1 : 0,
		};

		OrderCreateController.setupOrder();
		OrderCreateController.getSelectOptions();
		OrderCreateController.viewApi();
		OrderCreateController.onEvents();
		OrderCreateController.confirmNavigationIfFormIsDirty();
	};
	OrderCreateController.setupOrder = function(){

		if($scope.searchParams.order){
			OrderCreateController.getOrder(Number($scope.searchParams.order)).then(OrderCreateController.handleOrder);
		} else {
			$scope.order.fabrics = [
				{
					fabric_type_type: 'face',
					canDelete: false,
				}
			];
			//load fabric data
			$timeout($scope.fabricApi.initiateFabrics);
		}
	};
	OrderCreateController.getSelectOptions = function(){

		return $q(getSelectOptionsPromise);

		function getSelectOptionsPromise(resolveGetSelectOptions, rejectGetSelectOptions){

			$scope.loadingSelectOptions = true;
			bestlineApi.order().selectOptions().then(getSelectOptionsSuccess, getSelectOptionsError);

			function getSelectOptionsSuccess(response){

				$scope.loadingSelectOptions = false;
				$scope.selectOptions = response.data;
				resolveGetSelectOptions(response.data);
			};
			function getSelectOptionsError(response){
				$scope.loadingSelectOptions = false;
				rejectGetSelectOptions(response);
			};
		};
	};
	OrderCreateController.viewApi = function(){

		$scope.changeCompany = function(companyId){

			if(companyId){
				$scope.addresses = [];
				$scope.order.copyBilling = true;
				$scope.order.company = filterFilter($scope.selectOptions.companies, {id: companyId}, true)[0];
				OrderCreateController.getAddresses(companyId, OrderCreateController.setAddressesAfterCompanyId);
				OrderCreateController.getContacts(companyId).then(OrderCreateController.setContact);
				$scope.fabricApi.changeCompanyId(companyId);
			}
		};
		$scope.onProductChange = function(productId){
			if(productId){
				var product = filterFilter($scope.selectOptions.products, {id: productId}, true)[0];
				$scope.order.product = product;
				$scope.order.ring_type_id = product.ring_type_id;
				$scope.orderForm.$setDirty();
			}
		};
		$scope.onContactChange = function(contact){
			if(contact){
				$scope.order.contact_id = contact.id;
				$scope.orderForm.$setDirty();
			}
		};
		$scope.onBillingAddressChange = function(address){
			if(address){
				$scope.order.billing_address_id = address.id;
				$scope.orderForm.$setDirty();
			}
		};
		$scope.onShippingAddressChange = function(address){
			if(address){
				$scope.order.shipping_address_id = address.id;
				$scope.orderForm.$setDirty();
			}
		};
		$scope.newBillingAddress = function(order){

			OrderCreateController.getNewAddress(order.company_id).then(newBillingAddressSuccess);
			function newBillingAddressSuccess(newAddress){
				newAddress.editing = true;
				newAddress.company_id = order.company_id;
				$scope.addresses.push(newAddress);
				$scope.billing_address = newAddress;
				$scope.orderForm.$setDirty();
			}
		};
		$scope.newShippingAddress = function(order){
			OrderCreateController.getNewAddress(order.company_id).then(newBillingAddressSuccess);
			function newBillingAddressSuccess(newAddress){
				newAddress.editing = true;
				newAddress.company_id = order.company_id;
				$scope.addresses.push(newAddress);
				order.copyBilling = false;
				$scope.shipping_address = newAddress;
				$scope.orderForm.$setDirty();
			}
		}
		$scope.newContact = function(company){

			$scope.loadingNewContact = true;
			bestlineApi.company().contact().newContact().then(newContactSuccess,newContactError);
			function newContactSuccess(response){
				response.data.editing = true;
				response.data.company_id = $scope.order.company_id;
				$scope.contact = response.data;
				$scope.contacts.push(response.data);
				$scope.loadingNewContact = undefined;
				$scope.orderForm.$setDirty();
			};
			function newContactError(){
				$scope.loadingNewContact = undefined;
			};
		};
		$scope.addNewFabric = function(){
			$scope.fabricApi.newRow();
		};
		$scope.addNewOption = function(){
			$scope.optionsApi.newOption();
		};
		$scope.saveOrder = function(order){
			OrderCreateController.saveOrder(order).then(saveOrderSuccess);

			function saveOrderSuccess(order){

				$window.location.href = '/order/'+ order.id +'/edit';
			};
		};
	};
	OrderCreateController.setInitialAddresses = function(){

		OrderCreateController.setAddress('billing_address', $scope.order.billing_address_id);
		if(!$scope.order.copyBilling){
			OrderCreateController.setAddress('shipping_address', $scope.order.shipping_address_id);
		}
	};
	OrderCreateController.setAddressesAfterCompanyId = function(){

		OrderCreateController.setAddress('billing_address');
		if(!$scope.order.copyBilling){
			OrderCreateController.setAddress('shipping_address');
		}
	};
	OrderCreateController.setAddress = function(addressScopeKey, addressId){

		if(addressId){

			addressId = Number(addressId);
			var filteredAddresses = filterFilter($scope.addresses, {id: addressId}, true);
			$scope[addressScopeKey] = filteredAddresses[0];
			$scope.order[addressScopeKey + '_id'] = addressId;
		} else {
			$scope[addressScopeKey] = $scope.addresses[0];
			$scope.order[addressScopeKey + '_id'] = $scope.addresses[0].id;
		}
	};
	OrderCreateController.getAddresses = function(companyId, callback){

		function setup(){

			$scope.loadingAddresses = true;
			bestlineApi.company(companyId).addresses().then(getAddressesSuccess, getAddressesError);
		};
		function getAddressesSuccess(response){

			$scope.addresses = response.data;

			if(typeof callback === "function"){
				callback();
			}

			$scope.loadingAddresses = false;
		};
		function getAddressesError(){
			$scope.loadingAddresses = false;
		};
		setup();
	};
	OrderCreateController.getContacts = function(companyId){

		return $q(getContactsPromise);

		function getContactsPromise(resolveGetContacts, rejectGetContacts){

			function setup(){

				$scope.loadingContacts = true;
				bestlineApi.company(companyId).contacts().then(getContactsSuccess, getContactsError);
			};
			function getContactsSuccess(response){
				$scope.contacts = response.data;
				$scope.loadingContacts = false;
				resolveGetContacts($scope.contacts);
			};
			function getContactsError(response){
				$scope.loadingContacts = false;
				rejectGetContacts(response);
			};
			setup();
		};
	};
	OrderCreateController.setContact = function(){

		if($scope.order && $scope.order.contact_id){

			var contactId = $scope.order.contact_id;
			var filteredContact = filterFilter($scope.contacts, {id: contactId}, true);
			$scope.contact = filteredContact[0];
		} else {
			$scope.contact = $scope.contacts[0];
			$scope.order.contact_id = $scope.contacts[0].id;
		}
	};
	OrderCreateController.getOrder = function(orderId, callback){

		return $q(getOrderPromise);

		function getOrderPromise(resolveGetOrder, rejectGetOrder){

			$scope.order.loading = true;
			bestlineApi.order(orderId).get().then(getOrderSuccess, getOrderError);

			function getOrderSuccess(response){
				$scope.order.loading = false;
				resolveGetOrder(response.data);
			};
			function getOrderError(){
				$scope.order.loading = false;
				rejectGetOrder();
			};
		};
	};
	OrderCreateController.handleOrder = function(data){

		$scope.order = $.extend($scope.order, data);

		//set same as billing checkbox
		if(data.billing_address_id !== data.shipping_address_id){
			$scope.order.copyBilling = false;
		} else {
			$scope.order.copyBilling = true;
		}

		OrderCreateController.getContacts($scope.order.company_id).then(OrderCreateController.setContact);
		OrderCreateController.getAddresses($scope.order.company_id, OrderCreateController.setInitialAddresses);

		//load fabric data
		$timeout($scope.fabricApi.initiateFabrics);

		//load option data
		$timeout($scope.optionsApi.loadOptions);
	};
	OrderCreateController.getNewAddress = function(companyId){

		return $q(getNewAddressPromise);

		function getNewAddressPromise(getNewAddressResolve, getNewAddressReject){
			bestlineApi.company(companyId).address().newAddress().then(getNewAddressSuccess, getNewAddressError);

			function getNewAddressSuccess(response){
				getNewAddressResolve(response.data);
			};
			function getNewAddressError(response){
				getNewAddressReject(response);
			};
		};
	};
	OrderCreateController.onEvents = function(){

		var onAddressDelete = $scope.$on('address-deleted', function(e, data){

			OrderCreateController.getAddresses($scope.order.company_id);
			$scope.orderForm.$setDirty();

		});

		var onContactDelete = $scope.$on('contact-deleted', function(e, data){

			OrderCreateController.getContacts($scope.order.company_id);
		});

		var checkForEmbellishment = $scope.$on('option-needs-embellishment', function(e){

			var foundEmbellishment = false;
			angular.forEach($scope.order.fabrics, checkEmbellishment);
			function checkEmbellishment(orderFabric, index){

				if(orderFabric.type && orderFabric.type.type === 'embellishment'){
					foundEmbellishment = true;
				}
			};

			if(!foundEmbellishment){
				alert('Please add this option to an embellishment fabric.');
			}
		});

		var onContactChanged = $scope.$on('contact-changed', function(e, data){

			$scope.onContactChange(data);
		});

		var onAddressChanged = $scope.$on('address-changed', function(e, data){

			var isBillingAddress = ($scope.billing_address && $scope.billing_address.id === data.id);
			var isShippingAddress = ($scope.shipping_address && $scope.shipping_address.id === data.id);

			if(isBillingAddress){
				$scope.onBillingAddressChange(data);
			} else if(isShippingAddress){
				$scope.onShippingAddressChange(data);
			}
		});

		$scope.$on('$destroy', onDestroy);
		function onDestroy(){
			onAddressChanged();
			onContactChanged();
			checkForEmbellishment();
			onContactDelete();
			onAddressDelete();
		}
	};
	OrderCreateController.saveOrder = function(order){

		return $q(saveOrderPromise);

		function saveOrderPromise(saveOrderResolve, saveOrderReject){

			$scope.savingOrder = true;
			order.apiError = undefined;
			bestlineApi.order(order.id).updateStep1(order).then(saveOrderSuccess, saveOrderError);

			function saveOrderSuccess(response){
				//$scope.savingOrder = false;
				saveOrderResolve(response.data);
			}
			function saveOrderError(response){

				$scope.savingOrder = false;
				order.apiError = response.data.message;
				saveOrderReject(response);
			}
		};
	};
	OrderCreateController.confirmNavigationIfFormIsDirty = function(){

		$window.onbeforeunload = function(event){

			if(!$scope.orderForm.$pristine && !$scope.savingOrder){
				return false;
			}
		}

		$scope.$on('$destroy', function() {
		    delete $window.onbeforeunload;
		});
	}
	OrderCreateController.setup();
}

OrderCreateController.$inject = [
  '$scope',
  '$location',
  '$timeout',
  '$q',
  '$window',
  'bestlineApi',
  'filterFilter'
];
}(window.jQuery || window.$, window.angular, window.Order));
