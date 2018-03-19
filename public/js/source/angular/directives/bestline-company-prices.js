(function ($, angular) {

    /**
     * @ngdoc directive
     * @name bestline.directive:bestlineCompanyPrices
     * @description
     * # bestlineCompanyPrices

     Use: <div bestline-company-prices bc-prices="[array of prices]" bc-parent="[fabric/option/product object]" bc-options="[object of options]"></div>
     */
    angular.module('bestline').directive('bestlineCompanyPrices', bestlineCompanyPrices);

    function bestlineCompanyPrices(){
        'use strict';

       return {
            restrict: 'A',
            controller: bestlineCompanyPricesController,
            controllerAs: 'vmPrices',
            scope:{
                bcOptions: '=?',
                bcPrices: '=',
                bcParent: '=',
            },
            templateUrl: '/js/source/angular/directives/bestline-company-prices.html',
        };
        function bestlineCompanyPricesController(
            $scope,
            $uibModal
        ){

            var vm = this;

            function setup(){

                $scope.bcOptions = $.extend(defaultOptions(), $scope.bcOptions);
                viewApi();
            }
            function viewApi(){
                vm.newCompanyPrice = function(){

                    var newPrice = {}
                    if($scope.bcOptions.type === "product"){
                        newPrice.product_id = $scope.bcParent.id;
                    } else if($scope.bcOptions.type === "fabric"){
                        newPrice.fabric_id = $scope.bcParent.id;
                    } else if($scope.bcOptions.type === "option"){
                        newPrice.option_id = $scope.bcParent.id;
                    }
                    $scope.bcPrices.unshift(newPrice);
                    openCompanyPriceEdit(newPrice)
                };
                vm.editCompanyPrice = function(price){
                    openCompanyPriceEdit(price)
                };
            }
            function openCompanyPriceEdit(companyPrice){

                var companyPriceCopy = angular.copy(companyPrice);
                var companyPriceEditModal = $uibModal.open(getUibModalOptions());
                companyPriceEditModal.result.then(handleEditSuccess, handleEditClose);
                function getUibModalOptions(){
                    return {
                        templateUrl: 'companyPriceFormModalTemplate.script',
                        controller: companyPriceEditModalController,
                        controllerAs: 'vmModal',
                        scope: $scope,
                        resolve: {
                          companyPrice: function () {
                            return companyPrice;
                          },
                          companyPrices: function(){
                            return $scope.bcPrices;
                          }
                        }
                    };
                }
                function companyPriceEditModalController(
                    $uibModalInstance,
                    companyPrice,
                    $scope,
                    $window,
                    companyPrices
                ){
                    var vm = this;

                    function setup(){
                        vm.companyPrice = companyPrice;
                        onEvents();
                    }
                    function onEvents(){

                        var onCompanyPriceSaved = $scope.$on('company-price-saved', function(e, savedCompanyPrice){
                            $uibModalInstance.close(savedCompanyPrice);
                        });
                        var onEditCancel = $scope.$on('company-price-edit-canceled', function(e, companyPrice){
                            $uibModalInstance.dismiss('cancel');
                        });
                        var onCompanyPriceDelete = $scope.$on('company-price-deleted', function(e, companyPrice){
                            var index = companyPrices.indexOf(companyPrice);
                            companyPrices.splice(index, 1);
                            $uibModalInstance.dismiss('cancel');
                        });

                        $scope.$on('$destroy', onDestroy);
                        function onDestroy(){
                            onCompanyPriceSaved();
                            onEditCancel();
                            onCompanyPriceDelete();
                        }
                    }
                    setup();
                }
                function handleEditSuccess(){
                    //nothing to see here folks
                }
                function handleEditClose(){

                    var priceIsNew = !companyPrice.id;
                    var index = $scope.bcPrices.indexOf(companyPrice);
                    var foundPrice = index > -1;
                    if(foundPrice && priceIsNew){ //delete companyPrice
                        $scope.bcPrices.splice(index, 1);
                    } else { //undo edits
                        $scope.bcPrices[index] = companyPriceCopy;
                    }
                }
                companyPriceEditModalController.$inject = [
                  '$uibModalInstance',
                  'companyPrice',
                  '$scope',
                  '$window',
                  'companyPrices'
                ];
            }
            setup();
        }
        bestlineCompanyPricesController.$inject = [
          '$scope',
          '$uibModal'
        ];
        function defaultOptions(){
            return {
                type: 'product', //i.e. product, fabric, option
                showMin: true, //i.e. true, false to toggle min_charge column
            }
        }
    };
}(window.jQuery || window.$, window.angular));
