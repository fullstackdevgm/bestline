(function ($, angular) {

    /**
     * @ngdoc directive
     * @name bestline.directive:bestlineProductForm
     * @description
     * # bestlineProductForm

     Use: <div bestline-product-form product="[product_object]" ng-if="[product_object]"></div>
     Requires This File: /js/source/angular/parts/forms/bestline-company-price-form.js
     */
    angular.module('bestline').directive('bestlineProductForm', bestlineProductForm);

    function bestlineProductForm(){
        'use strict';

        return {
            restrict: 'A',
            controller: bestlineProductFormController,
            controllerAs: 'vmProductForm',
            scope:{
                product: '='
            },
            templateUrl: '/js/source/angular/parts/forms/bestline-product-form.html'
        };
        function bestlineProductFormController(
            $scope,
            $q,
            bestlineApi,
            $uibModal
        ){
            var vm = this;

            function setup(){
                vm.showFormErrors = false;
                vm.apiErrors = [];
                vm.companyPricesOptions = {type: 'product', showMin: false};

                viewApi();
                getProductFormData();
            }
            function viewApi(){
                vm.saveProduct = function(product, productForm){

                    vm.showFormErrors = true;
                    product.apiErrors = [];
                    if(productForm.$invalid){
                      product.apiErrors.push('The form has errors above.');
                      return false
                    }
                    saveProduct(product);
                };
                vm.saveProductAndClose = function(product, productForm){

                    vm.showFormErrors = true;
                    product.apiErrors = [];
                    if(productForm.$invalid){
                      product.apiErrors.push('The form has errors above.');
                      return false
                    }
                    saveProduct(product).then(saveProductSuccess);
                    function saveProductSuccess(response){
                        $scope.$emit('product-edit-complete', response.data);
                    }
                };
                vm.cancelEdit = function(){
                    $scope.$emit('product-edit-canceled', $scope.product);
                };
                vm.deleteProduct = function(product){
                    deleteProduct(product);
                };
            }
            function getProductFormData(){
                vm.loadingData = true;
                vm.apiErrors = [];
                var product = $scope.product;

                $q.all([getSelectOptions(), getCompanyPrices(product)]).then(getRequiredDataSuccess, getRequiredDataError);
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

                    bestlineApi.parts().products().selectOptions().then(getSelectOptionsSuccess, getSelectOptionsError);
                    function getSelectOptionsSuccess(response){
                        vm.selectOptions = response.data;
                        getSelectOptionsResolve(response);
                    }
                    function getSelectOptionsError(response){
                        getSelectOptionsReject(response);
                    }
                }
            }
            function getCompanyPrices(product){

                return $q(getCompanyPricesPromise);

                function getCompanyPricesPromise(getCompanyPricesResolve, getCompanyPricesReject){

                    var companyId = null;
                    var productId = product.id;

                    if(!productId){
                        var response = {
                            data: [],
                        }
                        getCompanyPricesSuccess(response);
                        return false;
                    }

                    bestlineApi.company(companyId).price().product(productId).then(getCompanyPricesSuccess, getCompanyPricesError);
                    function getCompanyPricesSuccess(response){
                        vm.companyPrices = response.data;
                        getCompanyPricesResolve(response);
                    }
                    function getCompanyPricesError(response){
                        getCompanyPricesReject(response);
                    }
                }
            }
            function saveProduct(product){

                return $q(saveProductPromise);

                function saveProductPromise(saveProductResolve, saveProductReject){

                    product.apiErrors = [];
                    product.busy = true;
                    var payload = product;
                    var productId = product.id;

                    bestlineApi.parts().products().save(productId, payload).then(saveProductSuccess, saveProductError);
                    function saveProductSuccess(response){
                        product.busy = false;
                        product.id = response.data.id;
                        saveProductResolve(response);
                    }
                    function saveProductError(response){
                        product.busy = false;
                        product.apiErrors.push(response.data.error.message);
                        saveProductReject(response);
                    }
                }
            }
            function deleteProduct(product){
                return $q(deleteProductPromise);

                function deleteProductPromise(deleteProductResolve, deleteProductReject){

                    var productId = product.id;
                    product.apiErrors = [];
                    product.busy = true;

                    if(!productId){
                        var response = {
                            data: product,
                        }
                        deleteProductSuccess(response);
                        return false;
                    }

                    bestlineApi.parts().products().delete(productId).then(deleteProductSuccess, deleteProductError);

                    function deleteProductSuccess(response){
                        $scope.$emit('product-deleted', product);
                        product.busy = false;
                        deleteProductResolve(response);
                    }
                    function deleteProductError(response){
                        product.busy = false;
                        product.apiErrors.push(response.data.error.message);
                        deleteProductReject(response);
                    }
                }
            }
            setup();
        }
        bestlineProductFormController.$inject = [
          '$scope',
          '$q',
          'bestlineApi',
          '$uibModal'
        ];
    };
}(window.jQuery || window.$, window.angular));