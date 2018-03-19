(function ($, angular) {

    /**
     * @ngdoc directive
     * @name bestline.directive:bestlineApiErrors
     * @description
     * # bestlineApiErrors

     Use: <div bestline-api-errors="" ></div>
     */
    angular.module('bestline').directive('bestlineApiErrors', bestlineApiErrors);

    function bestlineApiErrors(){
        'use strict';

        var dbugThis = false; var dbugAll = false;
        if(dbugAll||dbugThis){console.log("%ccalled directive:bestlineApiErrors()","color:orange");}

       return {
            restrict: 'A',
            controller: bestlineApiErrorsController,
            controllerAs: 'vmErrors',
            scope:{
                bestlineApiErrors: '='
            },
            templateUrl: '/js/source/angular/views/directives/bestline-api-errors.html',
        };
        function bestlineApiErrorsController($scope){}
    };
}(window.jQuery || window.$, window.angular));