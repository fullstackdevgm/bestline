(function ($, angular) {

    /**
     * @ngdoc directive
     * @name bestline.directive:bestlineCompanyPriceForm
     * @description
     * # bestlineCompanyPriceForm

     Use: <div bestline-company-price-form company-price="[company-price-object]" ></div>
     */
    angular.module('bestline').directive('bestlineCompanyPriceForm', bestlineCompanyPriceForm);

    function bestlineCompanyPriceForm(){
        'use strict';

        return {
            restrict: 'A',
            controller: bestlineCompanyPriceFormController,
            controllerAs: 'vmCompanyPriceForm',
            scope:{
                companyPrice: '='
            },
            templateUrl: '/js/source/angular/parts/forms/bestline-company-price-form.html'
        };
        function bestlineCompanyPriceFormController(
            $scope,
            $q,
            bestlineApi,
            $uibModal
        ){
            var vm = this;

            function setup(){
                vm.showFormErrors = false;
                vm.apiErrors = [];
                vm.showProduct = ($scope.companyPrice.product_id)? true : false;
                vm.showFabric = ($scope.companyPrice.fabric_id)? true : false;
                vm.showOption = ($scope.companyPrice.option_id)? true : false;

                viewApi();
                getSelectOptions();
            }
            function viewApi(){
                vm.saveCompanyPrice = function(companyPrice, companyPriceForm){

                    vm.showFormErrors = true;
                    companyPrice.apiErrors = [];
                    if(companyPriceForm.$invalid){
                      companyPrice.apiErrors.push('The form has errors above.');
                      return false
                    }
                    saveCompanyPrice(companyPrice);
                };
                vm.cancelEdit = function(){
                    $scope.$emit('company-price-edit-canceled', $scope.companyPrice);
                };
                vm.deleteCompanyPrice = function(price){
                    deleteCompanyPrice(price);
                };
            }
            function getSelectOptions(){

                return $q(getSelectOptionsPromise);

                function getSelectOptionsPromise(getSelectOptionsResolve, getSelectOptionsReject){

                    vm.loadingData = true;
                    vm.apiErrors = [];
                    var companyId = null;
                    bestlineApi.company(companyId).price().selectOptions().then(getSelectOptionsSuccess, getSelectOptionsError);
                    function getSelectOptionsSuccess(response){
                        vm.loadingData = false;
                        vm.selectOptions = response.data;
                        getSelectOptionsResolve(response);
                    }
                    function getSelectOptionsError(response){
                        vm.loadingData = false;
                        vm.apiErrors.push(response.data.error.message);
                        getSelectOptionsReject(response);
                    }
                }
            }
            function saveCompanyPrice(companyPrice){

                return $q(saveCompanyPricePromise);

                function saveCompanyPricePromise(saveCompanyPriceResolve, saveCompanyPriceReject){

                    companyPrice.apiErrors = [];
                    companyPrice.busy = true;
                    var payload = companyPrice;
                    var companyId = companyPrice.company_id;

                    bestlineApi.company(companyId).price().save(payload).then(saveCompanyPriceSuccess, saveCompanyPriceError);
                    function saveCompanyPriceSuccess(response){
                        companyPrice.busy = false;
                        companyPrice.id = response.data.id;
                        companyPrice.company = response.data.company;
                        $scope.$emit('company-price-saved', response.data);
                        saveCompanyPriceResolve(response);
                    }
                    function saveCompanyPriceError(response){
                        companyPrice.busy = false;
                        companyPrice.apiErrors.push(response.data.error.message);
                        saveCompanyPriceReject(response);
                    }
                }
            }
            function deleteCompanyPrice(companyPrice){

                return $q(deleteCompanyPricePromise);

                function deleteCompanyPricePromise(deleteCompanyPriceResolve, deleteCompanyPriceReject){

                    companyPrice.apiErrors = [];
                    companyPrice.busy = true;
                    var priceId = companyPrice.id;
                    var companyId = null;

                    if(!priceId){
                        var response = {
                            data: companyPrice,
                        }
                        deleteCompanyPriceSuccess(response);
                        return false;
                    }

                    bestlineApi.company(companyId).price(priceId).delete().then(deleteCompanyPriceSuccess, deleteCompanyPriceError);
                    function deleteCompanyPriceSuccess(response){
                        companyPrice.busy = false;
                        $scope.$emit('company-price-deleted', companyPrice);
                        deleteCompanyPriceResolve(response);
                    }
                    function deleteCompanyPriceError(response){
                        companyPrice.busy = false;
                        companyPrice.apiErrors.push(response.data.error.message);
                        deleteCompanyPriceReject(response);
                    }
                }
            }
            setup();
        }
        bestlineCompanyPriceFormController.$inject = [
          '$scope',
          '$q',
          'bestlineApi',
          '$uibModal'
        ];
    };
}(window.jQuery || window.$, window.angular));