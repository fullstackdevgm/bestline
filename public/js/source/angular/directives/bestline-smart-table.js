(function ($, angular) {

    /**
     * @ngdoc directive
     * @name bestline.directive:bestlineSmartTable
     * @description
     * # bestlineSmartTable

     Use: <div bestline-smart-table st-api="CONTROLLER_OBJECT_TO_ACT_AS_API"></div>
     */
    angular.module('bestline').directive('bestlineSmartTable', bestlineSmartTable);

    function bestlineSmartTable(){
        'use strict';

       return {
            restrict: 'A',
            require:'^stTable',
            link: bestlineSmartTableLink,
            scope:{
                stApi: "="
            },
        };

        function bestlineSmartTableLink(scope, element, attr, ctrl){

            scope.stApi = ctrl;
        };
    };
}(window.jQuery || window.$, window.angular));