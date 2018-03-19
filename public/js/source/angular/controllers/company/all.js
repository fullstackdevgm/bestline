(function ($, angular) {
'use strict';

/**
 * @ngdoc function
 * @name bestline.controller:CompanyAllController
 * @description
 */
angular.module('bestline')
.controller('CompanyAllController', function ($scope, $q, bestlineApi, bestlineForms) {

	var vm = this;
	var CompanyAllController = {}; //private object
	CompanyAllController.setup = function(){

		//set scope variables here
		$scope.loadingCompanies = true;
		$scope.loadingNewCompany = false;
		$scope.companies = [];
		$scope.companySelectOptions = [];
		vm.sortSlug = 'created_at';
		vm.sortDesc = true;

		CompanyAllController.viewApi();
		CompanyAllController.getCompanies();
		CompanyAllController.getSelectOptions();
	};
	CompanyAllController.viewApi = function(){

		//functions to be used in view can be added to $scope here
		$scope.getCustomerTypeName = function(id){

			if($scope.companySelectOptions.length !== 0 && id){
				return $scope.companySelectOptions.customer_types.filter(function(a){ return a.id == id })[0].name;
			}
		};
		$scope.loadCompany = function(company){

			company.updating = true;
			company.companyLoaded = false;
			CompanyAllController.getAddressesContacts(company).then(getAddressesContactsSuccess);
			function getAddressesContactsSuccess(response){
				company.companyLoaded = true;
				company.updating = false;
			}
		}
		$scope.startCompanyEdit = function(company, companyForm){

			companyForm.companyCopy = angular.fromJson(angular.toJson(company));
			if(companyForm.companyCopy.addresses){
				companyForm.companyCopy.addresses = undefined;
			}
			if(companyForm.companyCopy.contacts){
				companyForm.companyCopy.contacts = undefined;
			}
			company.editing = true;
		};
		$scope.cancelCompanyEdit = function(company, companyForm){

			//if form is dirty, reset the values to before the edit
			if(companyForm.$dirty){
				bestlineForms.resetFormValidity(companyForm, 'baseline');
				companyForm.$setUntouched();
				companyForm.$setPristine();
				company = $.extend(company, companyForm.companyCopy);
				company.loadingAddresses = false;
				company.loadingContacts = false;
			}
			company.editing = false;
		};
		$scope.addCompany = function(){

			$scope.loadingNewCompany = true;
			vm.sortSlug = 'created_at';
			vm.sortDesc = true;
			bestlineApi.company().newCompany().then(function(response){

				response.data.companyLoaded = true;
				response.data.editing = true;
				$scope.companies.unshift(response.data);
				$scope.loadingNewCompany = false;
			});
		};
		$scope.saveCompany = function(company, companyForm){
			
			var data = angular.toJson(company);
			var resource = bestlineApi.company(company.id).update(data);
			bestlineForms.updateResource(resource, company, companyForm).then(updateResourceSuccess);
			function updateResourceSuccess(){
				company.editing = false;
			}
		};
		$scope.deleteCompany = function(company, companyForm){

			if(!company.id){
				deleteCompanySuccess();
			} else {
				var resource = bestlineApi.company(company.id);
				bestlineForms.deleteResource(resource, company).then(deleteCompanySuccess);
			}
			function deleteCompanySuccess(){

				var index = $scope.companies.indexOf(company);
				$scope.companies.splice(index, 1);
			};
		};
		$scope.startEdit = function(details, detailsForm, remove){ 

			bestlineForms.startEdit(details, detailsForm, remove);
		};
		$scope.cancelEdit = function(details, detailsForm){

			bestlineForms.cancelEdit(details, detailsForm);
		};
		$scope.addAddress = function(company){

			company.loadingAddresses = true;
			bestlineApi.company().address().newAddress().then(newAddressSuccess, newAddressError);
			function newAddressSuccess(response){
				response.data.editing = true; 
				response.data.company_id = company.id;
				if(!company.addresses){
					company.addresses = [];
				}
				company.addresses.unshift(response.data);
				company.loadingAddresses = undefined;
			};
			function newAddressError(){
				company.loadingAddresses = undefined;
			};
		};
		$scope.addContact = function(company){

			company.loadingContacts = true;
			bestlineApi.company().contact().newContact().then(function(response){

				response.data.editing = true;
				response.data.company_id = company.id;
				if(!company.contacts){
					company.contacts = [];
				}
				company.contacts.unshift(response.data);
				company.loadingContacts = undefined;
			}, function(){
				company.loadingContacts = undefined;
			});
		};
	};
	CompanyAllController.getCompanies = function(){

		var getCompanies = {};
		
		getCompanies.setup = function(){

			bestlineApi.company().all().then(getCompanies.success, getCompanies.failure);
		};
		getCompanies.success = function(response){

			$scope.companies = response.data;
			getCompanies.finish();
		};
		getCompanies.error = function(response){

			getCompanies.finish();
		};
		getCompanies.finish = function(){

			$scope.loadingCompanies = false;
		};
		getCompanies.setup();
	};
	CompanyAllController.getSelectOptions = function(){
	
		var getSelectOptions = {};
		
		getSelectOptions.setup = function(){
	
			bestlineApi.company().selectOptions().then(getSelectOptions.success,getSelectOptions.failure);
		};
		getSelectOptions.success = function(response){
			
			$scope.companySelectOptions = response.data;
			getSelectOptions.finish();
		};
		getSelectOptions.error = function(response){
	
			getSelectOptions.finish();
		};
		getSelectOptions.finish = function(){};
		getSelectOptions.setup();
	};
	CompanyAllController.getAddressesContacts = function(company){

		var promises = [];

		if(!company.addresses || company.addresses.length === 0){
			promises.push(CompanyAllController.getAddresses(company));
		}

		if(!company.contacts || company.contacts.length === 0){
			promises.push(CompanyAllController.getContacts(company));
		}

		return $q.all(promises);
	};
	CompanyAllController.getAddresses = function(company){

		company.loadingAddresses = true;

		return $q(getAddressesPromise);
		function getAddressesPromise(getAddressesResolve, getAddressesReject){
			bestlineApi.company(company.id).addresses().then(getAddressesSuccess, getAddressesError);

			function getAddressesSuccess(response){
				company.addresses = response.data;
				company.loadingAddresses = false;
				getAddressesResolve(response);
			};
			function getAddressesError(response){
				company.loadingAddresses = false;
				getAddressesReject(response);
			};
		};
	};
	CompanyAllController.getContacts = function(company){

		company.loadingContacts = true;

		return $q(getContactsPromise);
		function getContactsPromise(getContactsResolve, getContactsReject){
			bestlineApi.company(company.id).contacts().then(getContactsSuccess, getContactsError);

			function getContactsSuccess(response){
				company.contacts = response.data;
				company.loadingContacts = false;
				getContactsResolve(response.data);
			};
			function getContactsError(response){
				company.loadingContacts = false;
				getContactsReject(response);
			};
		};
	};
	CompanyAllController.setup();
});
}(window.jQuery || window.$, window.angular));