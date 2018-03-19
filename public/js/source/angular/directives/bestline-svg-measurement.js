/**
 * @ngdoc directive
 * @name bestline.directive:bestlineSvgMeasurement
 * @description
 * # bestlineSvgMeasurement

 Use: <div default-directive="" ></div>
 */
angular.module('bestline')
.directive('bestlineSvgMeasurement', function() {
    'use strict';

    var dbugThis = false; var dbugAll = false;
    if(dbugAll||dbugThis){console.log("%ccalled directive:bestlineSvgMeasurement()","color:orange");}

    var bestlineSvgMeasurement = {
        restrict: 'E',
        replace: true,
        scope:{
            measurements: "=",
        },
        templateUrl: '/js/source/angular/views/directives/bestline-svg-measurement.html',
    };

    bestlineSvgMeasurement.link = function($scope, element, attrs, controller){

        //var dbugThis = true;
        if(dbugAll||dbugThis){console.log("%ccalled bestlineSvgMeasurement.link()","color:orange");}

        var bestlineSvgMeasurementLink = {}; //private object

        bestlineSvgMeasurementLink.setup = function(){

            bestlineSvgMeasurementLink.modifyMeasurements();
        };
        bestlineSvgMeasurementLink.modifyMeasurements = function(){
            angular.forEach($scope.measurements, measurementDefaults);
            function measurementDefaults(measurement, index){

                var defaults = {
                    x: 0,
                    y: 0,
                    height: 0,
                    flip: false,
                    rotate: false,
                    width: 1.5,
                    textAnchor: 'end',
                    textX: 0,
                    transform: '', //can be any valid svg transform
                    fontSize: 1.5,
                    lineWidth: 0.225,
                    textLabel: null, //can add text to appear after the measurement text
                    textAlignment: 'central',//any alignment-baseline svg value
                };

                $scope.measurements[index] = $.extend(defaults, $scope.measurements[index]);

                if($scope.measurements[index].flip){
                    $scope.measurements[index].textAnchor = "start"; //can be end, start, middle
                    $scope.measurements[index].textX = $scope.measurements[index].width;
                }
                if($scope.measurements[index].rotate){
                    $scope.measurements[index].transform = 'rotate(-90 0 0)';
                    $scope.measurements[index].textTransform = 'rotate(90 '+ $scope.measurements[index].textX +' '+ $scope.measurements[index].height * 0.5 +')';
                }
            }
        };
        bestlineSvgMeasurementLink.setup();
    };

    return bestlineSvgMeasurement;
});
