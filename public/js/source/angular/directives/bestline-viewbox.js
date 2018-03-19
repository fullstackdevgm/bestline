/**
 * @ngdoc directive
 * @name bestline.directive:bestlineViewbox
 * @description
 * # bestlineViewbox

 Use: <div default-directive="" ></div>
 */
angular.module('bestline')
.directive('bestlineViewbox', function() {
    'use strict';

    var dbugThis = false; var dbugAll = false;
    if(dbugAll||dbugThis){console.log("%ccalled bestlineViewbox()","color:orange");}

    var bestlineViewbox = {};

    bestlineViewbox.link = function($scope, element, attrs){

        attrs.$observe('bestlineViewbox', function (value) {
           element.context.setAttribute('viewBox', value);
       });
    };

    return bestlineViewbox;
});
