(function ($, angular) {

    /**
     * @ngdoc directive
     * @name bestline.directive:bestlineFabricTable
     * @description
     * # bestlineFabricTable

     Usage: <div bestline-fabric-table="" fabric-table-rows="[array of fabrics]" fabric-table-options="[object of options]"></div>
     Dependents: must inject angular-smart-table into bestline (e.g. <script src="/js/source/angular/includes/inject-angular-smart-table.js" type="text/javascript"></script>)
     */
    angular.module('bestline').directive('bestlineFabricTable', bestlineFabricTable);

    function bestlineFabricTable(){
        'use strict';

       return {
            restrict: 'A',
            controller: bestlineFabricTableController,
            controllerAs: 'vmTable',
            scope:{
                fabricTableRows: '=',
                fabricTableOptions: '=',
            },
            templateUrl: '/js/source/angular/inventory/dashboard/components/fabric-table/fabric-table.view.html',
        };
        function bestlineFabricTableController($scope, $window){

            var vm = this;

            function setup(){

                $scope.fabricTableOptions = $.extend(true, defaultOptions(), $scope.fabricTableOptions);

                viewFunction();
            }
            function viewFunction(){}
            setup();
        }
        function defaultOptions(){
            return {
                showColumns: {
                    company: true,
                    sidemark: true,
                    com: true,
                }
            }
        }
    };
}(window.jQuery || window.$, window.angular));