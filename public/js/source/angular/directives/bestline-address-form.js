/**
 * @ngdoc directive
 * @name bestline.directive:bestlineAddressForm
 * @description
 * # bestlineAddressForm

 Use: <div bestline-address-form="{[see @params below]}"></div>

 @params (keys passed into the directive's scope)
    address
    states //array of states (see CompanyAllController)
    shippingMethods //array of shippingMethods (see CompanyAllController)
    company //optional, company object (CompanyAllController)
    companyForm //optional, angular form of company inputs (see CompanyAllController)
 */
angular.module('bestline')
.directive('bestlineAddressForm', function($rootScope, bestlineApi, bestlineForms) {
    'use strict';

    var bestlineAddressForm = {
        restrict: 'A',
        scope:{
            baf: '=bestlineAddressForm'
        },
        replace: true,
        templateUrl: '/js/source/angular/views/directives/bestline-address-form.html',
    };

    bestlineAddressForm.link = function($scope){

        var bestlineAddressFormLink = {}; //private object

        bestlineAddressFormLink.setup = function(){

            bestlineAddressFormLink.viewApi();
        };
        bestlineAddressFormLink.viewApi = function(){

            $scope.startEdit = function(details, detailsForm, remove){ 

                bestlineForms.startEdit(details, detailsForm, remove);
            };
            $scope.cancelEdit = function(details, detailsForm){

                bestlineForms.cancelEdit(details, detailsForm)
            };
            $scope.setParentKey = function(parent, parentForm, key, value){
                
                bestlineForms.setParentKey(parent, parentForm, key, value);
            };
            $scope.saveAddress = function(address, addressForm){

                if(!address.company_id){

                    if(!$scope.baf.company.id){
                        address.apiError = 'You must save the company first.';
                        return false;
                    } else {
                        address.company_id = $scope.baf.company.id;
                    }
                }

                var data = angular.toJson(address);
                var resource = bestlineApi.company(address.company_id).address(address.id).update(data);
                bestlineForms.updateResource(resource, address, addressForm).then(saveAddressSuccess);

                function saveAddressSuccess(response){
                    address.editing = false;
                    $scope.$emit('address-changed', response.data);
                };
            };
            $scope.deleteAddress = function(address){

                if(!address.id){
                    bestlineForms.handleDeleteSuccess(address);
                    deleteAddressSuccess();
                } else {
                    var resource = bestlineApi.company(address.company_id).address(address.id);
                    bestlineForms.deleteResource(resource, address).then(deleteAddressSuccess);
                }

                function deleteAddressSuccess(){
                    $rootScope.$broadcast('address-deleted');
                };
            };
            $scope.getShippingMethodName = function(id){

                if($scope.baf.shippingMethods.length !== 0 && id){
                    return $scope.baf.shippingMethods.filter(function(a){ return a.id == id })[0].name;
                }
            };
        };
        bestlineAddressFormLink.setup();
    };

    return bestlineAddressForm;
});
