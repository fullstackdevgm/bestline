(function ($, angular) {

    /**
     * @ngdoc directive
     * @name bestline.directive:bestlineTooltip
     * @description
     * # bestlineTooltip

     Use: <div bestline-tooltip>tooltip plain text or html</div>
     */
    angular.module('bestline').requires.push('ui.bootstrap');
    angular.module('bestline').directive('bestlineTooltip', bestlineTooltip);

    function bestlineTooltip($compile, $sce){
        'use strict';

       return {
            restrict: 'A',
            controller: bestlineTooltipController,
            controllerAs: 'vmTooltip',
            scope:{
                tooltipPopoverHtml: '@'
            },
            link: bestlineTooltipLink,
        };
        function bestlineTooltipController(
            $scope,
            $element
        ){
            var vm = this;

            function setup(){

                $scope.tooltipPopoverHtml = $sce.trustAsHtml($element.html());
            }
            setup();
        }
        bestlineTooltipController.$inject = [
          '$scope',
          '$element'
        ];
        function bestlineTooltipLink($scope, element, attrs){

            var bestlineTooltipLink = {}; //private object

            function setup(){

                var tooltipHtml = '<span class="bestlineTooltip" uib-popover-html="tooltipPopoverHtml" popover-trigger="\'mouseenter\'" popover-popup-delay="1000"><i class="fa fa-question-circle"></i></span>';
                var tooltipElement = $compile(tooltipHtml)($scope);

                element.addClass('bestlineTooltipWrapper');
                element.empty();
                element.append(tooltipElement);
            }
            setup();
        }
    };
}(window.jQuery || window.$, window.angular));