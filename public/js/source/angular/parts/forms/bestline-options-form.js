(function ($, angular) {

    /**
     * @ngdoc directive
     * @name bestline.directive:bestlineOptionForm
     * @description
     * # bestlineOptionForm

     Use: <div bestline-option-form option="[option_object]" ng-if="[option_object]"></div>
     Requires This File: /js/source/angular/parts/forms/bestline-company-price-form.js
     */
    angular.module('bestline').directive('bestlineOptionForm', bestlineOptionForm);

    function bestlineOptionForm(){
        'use strict';

        return {
            restrict: 'A',
            controller: bestlineOptionFormController,
            controllerAs: 'vmOptionForm',
            scope:{
                option: '='
            },
            templateUrl: '/js/source/angular/parts/forms/bestline-options-form.html'
        };
        function bestlineOptionFormController(
            $scope,
            $q,
            bestlineApi,
            $uibModal,
            $window
        ){
            var vm = this;

            function setup(){
                vm.showFormErrors = false;
                vm.apiErrors = [];
                vm.companyPricesOptions = {type: 'option'};

                viewApi();
                getOptionFormData();
            }
            function viewApi(){
                vm.saveOption = function(option, optionForm){

                    vm.showFormErrors = true;
                    option.apiErrors = [];
                    if(optionForm.$invalid){
                      option.apiErrors.push('The form has errors above.');
                      return false
                    }

                    var isNew = option.isNew;
                    saveOption(option).then(function(savedOption){
                        if(isNew){
                            $window.location.href = "/parts/options/" + savedOption.id;
                        }
                    });
                };
                vm.saveOptionAndClose = function(option, optionForm){

                    vm.showFormErrors = true;
                    option.apiErrors = [];
                    if(optionForm.$invalid){
                      option.apiErrors.push('The form has errors above.');
                      return false
                    }
                    saveOption(option).then(saveOptionSuccess);
                    function saveOptionSuccess(savedOption){
                        $scope.$emit('option-edit-complete', savedOption);
                    }
                };
                vm.cancelEdit = function(){
                    $scope.$emit('option-edit-canceled', $scope.option);
                };
                vm.deleteOption = function(option){
                    deleteOption(option);
                };
            }
            function getOptionFormData(){
                vm.loadingData = true;
                vm.apiErrors = [];
                var option = $scope.option;

                $q.all([getSelectOptions(), getCompanyPrices(option)]).then(getRequiredDataSuccess, getRequiredDataError);
                function getRequiredDataSuccess(response){
                    vm.loadingData = false;
                }
                function getRequiredDataError(response){
                    vm.loadingData = false;
                    vm.apiErrors.push(response.data.error.message);
                }
            }
            function getSelectOptions(){

                return $q(getSelectOptionsPromise);

                function getSelectOptionsPromise(getSelectOptionsResolve, getSelectOptionsReject){

                    bestlineApi.parts().options().selectOptions().then(getSelectOptionsSuccess, getSelectOptionsError);
                    function getSelectOptionsSuccess(response){
                        vm.selectOptions = response.data;
                        getSelectOptionsResolve(response);
                    }
                    function getSelectOptionsError(response){
                        getSelectOptionsReject(response);
                    }
                }
            }
            function getCompanyPrices(option){

                return $q(getCompanyPricesPromise);

                function getCompanyPricesPromise(getCompanyPricesResolve, getCompanyPricesReject){

                    var companyId = null;
                    var optionId = option.id;

                    if(!optionId){
                        var response = {
                            data: [],
                        }
                        getCompanyPricesSuccess(response);
                        return false;
                    }

                    bestlineApi.company(companyId).price().option(optionId).then(getCompanyPricesSuccess, getCompanyPricesError);
                    function getCompanyPricesSuccess(response){
                        vm.companyPrices = response.data;
                        getCompanyPricesResolve(response);
                    }
                    function getCompanyPricesError(response){
                        getCompanyPricesReject(response);
                    }
                }
            }
            function saveOption(option){

                return $q(saveOptionPromise);

                function saveOptionPromise(saveOptionResolve, saveOptionReject){

                    option.apiErrors = [];
                    option.busy = true;
                    var payload = option;
                    var optionId = option.id;

                    bestlineApi.parts().options().save(optionId, payload).then(saveOptionSuccess, saveOptionError);
                    function saveOptionSuccess(response){
                        option.busy = false;
                        option.id = response.data.id;
                        saveOptionResolve(response.data);
                    }
                    function saveOptionError(response){
                        option.busy = false;
                        option.apiErrors.push(response.data.error.message);
                        saveOptionReject(response);
                    }
                }
            }
            function deleteOption(option){
                return $q(deleteOptionPromise);

                function deleteOptionPromise(deleteOptionResolve, deleteOptionReject){

                    var optionId = option.id;
                    option.apiErrors = [];
                    option.busy = true;

                    if(!optionId){
                        var response = {
                            data: option,
                        }
                        deleteOptionSuccess(response);
                        return false;
                    }

                    bestlineApi.parts().options().delete(optionId).then(deleteOptionSuccess, deleteOptionError);

                    function deleteOptionSuccess(response){
                        $scope.$emit('option-deleted', option);
                        option.busy = false;
                        deleteOptionResolve(response);
                    }
                    function deleteOptionError(response){
                        option.busy = false;
                        option.apiErrors.push(response.data.error.message);
                        deleteOptionReject(response);
                    }
                }
            }
            setup();
        }
        bestlineOptionFormController.$inject = [
          '$scope',
          '$q',
          'bestlineApi',
          '$uibModal',
          '$window'
        ];
    };
}(window.jQuery || window.$, window.angular));