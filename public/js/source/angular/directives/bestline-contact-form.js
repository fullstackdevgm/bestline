(function ($, angular) {

    /**
     * @ngdoc directive
     * @name bestline.directive:bestlineContactForm
     * @description
     * # bestlineContactForm

     Use: <bestline-contact-form bcf="{[see @params below]}"></bestline-contact-form>

     @params (keys passed into the directive's scope)
        contact
        phoneTypes //array of phone_type
     */
    angular.module('bestline').directive('bestlineContactForm', bestlineContactForm);

    function bestlineContactForm(){
        'use strict';

       return {
            restrict: 'E',
            controller: bestlineContactFormController,
            controllerAs: 'vm',
            scope:{
                bcf: '=',
            },
            replace: true,
            templateUrl: '/js/source/angular/views/directives/bestline-contact-form.html',
        };

        function bestlineContactFormController($rootScope, $scope, bestlineApi, bestlineForms){

            var vm = this;

            function setup(){
                viewApi();
            };
            function viewApi(){

                $scope.viewContact = function(contact){

                    contact.viewContact = true;
                }
                $scope.startEdit = function(details, detailsForm, remove){ 

                    bestlineForms.startEdit(details, detailsForm, remove);
                };
                $scope.cancelEdit = function(details, detailsForm){

                    bestlineForms.cancelEdit(details, detailsForm)
                };
                $scope.saveContact = function(contact, contactForm, company, stopEditing){

                    if(!contact.company_id){

                        if(!company.id){
                            contact.apiError = 'You must save the company first.';
                            return false;
                        } else {
                            contact.company_id = company.id;
                        }
                    }

                    var data = angular.toJson(contact);
                    var resource = bestlineApi.company(contact.company_id).contact(contact.id).update(data);
                    bestlineForms.updateResource(resource, contact, contactForm).then(saveContactSuccess);
                    function saveContactSuccess(response){
                        if(stopEditing){

                            contact.editing = false;
                        }
                        $scope.$emit('contact-changed', response.data);
                    };
                };
                $scope.deleteContact = function(contact){
                    if(!contact.id){
                        bestlineForms.handleDeleteSuccess(contact);
                        deleteContactSuccess();
                    } else {
                        var resource = bestlineApi.company(contact.company_id).contact(contact.id);
                        bestlineForms.deleteResource(resource, contact).then(deleteContactSuccess);
                    }

                    function deleteContactSuccess(){
                        $rootScope.$broadcast('contact-deleted');
                    };
                };
                $scope.addEmail = function(contact){

                    contact.loadingEmails = true;
                    bestlineApi.company().contact().email().newEmail().then(function(response){

                        response.data.editing = true;
                        response.data.contact_id = contact.id;
                        if(!contact.emails){
                            contact.emails = [];
                        }
                        contact.emails.unshift(response.data);
                        contact.loadingEmails = undefined;
                    }, function(){
                        contact.loadingEmails = undefined;
                    });
                };
                $scope.saveEmail = function(email, emailForm, contact){

                    if(!email.contact_id){

                        if(!contact.id){
                            email.apiError = 'You must save the contact first.';
                            return false;
                        } else {
                            email.contact_id = contact.id;
                        }
                    }

                    var data = angular.toJson(email);
                    var resource = bestlineApi.company().contact(email.contact_id).email(email.id).update(data);
                    bestlineForms.updateResource(resource, email, emailForm).then(updateEmailSuccess);
                    function updateEmailSuccess(){
                        email.editing = false;
                    }
                };
                $scope.deleteEmail = function(email){
                    if(!email.id){
                        bestlineForms.handleDeleteSuccess(email);
                    } else {
                        var resource = bestlineApi.company().contact().email(email.id);
                        bestlineForms.deleteResource(resource, email);
                    }
                };
                $scope.addPhoneNumber = function(contact){

                    contact.loadingPhoneNumbers = true;
                    bestlineApi.company().contact().phone().newPhone().then(function(response){

                        response.data.editing = true;
                        response.data.contact_id = contact.id;
                        if(!contact.phone_numbers){
                            contact.phone_numbers = [];
                        }
                        contact.phone_numbers.unshift(response.data);
                        contact.loadingPhoneNumbers = undefined;
                    }, function(){
                        contact.loadingPhoneNumbers = undefined;
                    });
                };
                $scope.savePhoneNumber = function(phone, phoneForm, contact){

                    if(!phone.contact_id){

                        if(!contact.id){
                            phone.apiError = 'You must save the contact first.';
                            return false;
                        } else {
                            phone.contact_id = contact.id;
                        }
                    }

                    var data = angular.toJson(phone);
                    var resource = bestlineApi.company().contact(phone.contact_id).phone(phone.id).update(data);
                    bestlineForms.updateResource(resource, phone, phoneForm).then(updatePhoneSuccess);
                    function updatePhoneSuccess(){
                        phone.editing = false;
                    }
                };
                $scope.deletePhoneNumber = function(phone){
                    if(!phone.id){
                        bestlineForms.handleDeleteSuccess(phone);
                    } else {
                        var resource = bestlineApi.company().contact().phone(phone.id);
                        bestlineForms.deleteResource(resource, phone);
                    }
                };
                $scope.getPrimaryEmail = function(contact){

                    if(!contact.emails || contact.emails.length === 0){
                        return null;
                    }

                    if(contact.primary_email_id){
                        var primaryEmail = filterArrayById(contact.emails, contact.primary_email_id);
                        if(primaryEmail){
                            return primaryEmail;
                        }
                    } 

                    var firstActiveEmail = null;
                    angular.forEach(contact.emails, function(email){
                        if(!email.deleted && !firstActiveEmail){
                            firstActiveEmail =  email;
                        }
                    });

                    return firstActiveEmail;
                };
                $scope.getPrimaryPhone = function(contact){

                    if(!contact.phone_numbers || contact.phone_numbers.length === 0){
                        return null;
                    }

                    if(contact.primary_phone_number_id){
                        var primaryPhone = filterArrayById(contact.phone_numbers, contact.primary_phone_number_id);
                        if(primaryPhone){
                            return primaryPhone;
                        }
                    } 

                    var activePhone = null;
                    angular.forEach(contact.phone_numbers, function(phone){
                        if(!phone.deleted && !activePhone){
                            activePhone =  phone;
                        }
                    });

                    return activePhone;
                };
                $scope.setParentKey = function(parent, parentForm, key, value){

                    bestlineForms.setParentKey(parent, parentForm, key, value);
                };
            };
            function filterArrayById(array, id){

                return array.filter(function(a){ return a.id == id })[0];
            };
            setup();
        };
    };
}(window.jQuery || window.$, window.angular));