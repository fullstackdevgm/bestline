(function ($, angular) {

    /**
     * @ngdoc directive
     * @name bestline.directive:bestlineCalculateTrimPoints
     * @description
     * # bestlineCalculateTrimPoints

     Use: <div bestline-calculate-trim-points="[shade column object]" ></div>
     */
    angular.module('bestline').directive('bestlineCalculateTrimPoints', bestlineCalculateTrimPoints);

    function bestlineCalculateTrimPoints($timeout){
        'use strict';

       return {
            restrict: 'A',
            link: bestlineCalculateTrimPointsLink,
            scope:{
                bestlineCalculateTrimPoints: '='
            }
        }
        function bestlineCalculateTrimPointsLink($scope, element, attrs){

            var bestlineCalculateTrimPointsLink = {}; //private object

            function setup(){
                findPoints();
            };
            function findPoints(){

                $timeout(waitTillLoaded);
                function waitTillLoaded(){

                    var pathEl = element.get(0);
                    var pathLength = pathEl.getTotalLength();
                    var tassleRepeatWidth = 1.5;
                    var trimPointsCount = Math.ceil(pathLength / tassleRepeatWidth);
                    $scope.bestlineCalculateTrimPoints.trimPoints = [];

                    for(var i = 0; i < trimPointsCount; i++){
                        var trimPointLength = i * tassleRepeatWidth;
                        var trimPointCoordinates = pathEl.getPointAtLength(trimPointLength);
                        $scope.bestlineCalculateTrimPoints.trimPoints.push(trimPointCoordinates);
                    }

                    var finalTrimPointCoordinates = pathEl.getPointAtLength(pathLength);
                    $scope.bestlineCalculateTrimPoints.trimPoints.push(finalTrimPointCoordinates);
                }
            }
            setup();
        }
    };
}(window.jQuery || window.$, window.angular));