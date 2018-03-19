/**
 * @ngdoc directive
 * @name bestline.directive:bestlineIsolateForm
 * @description
 * # bestlineIsolateForm

 Use: <div default-directive="" ></div>
 */
angular.module('bestline')
.directive('bestlineIsolateForm', function() {
    'use strict';

    var dbugThis = false; var dbugAll = false;
    if(dbugAll||dbugThis){console.log("%ccalled bestlineIsolateForm()","color:orange");}

    var bestlineIsolateForm = {
        restrict: 'A',
        require: '?form',
    };

    bestlineIsolateForm.link = function($scope, element, attrs, ctrl){

        var bestlineIsolateFormLink = {}; //private object

        bestlineIsolateFormLink.setup = function(){

            if (!ctrl) {
                return;
            }

            // Do a copy of the controller
            var ctrlCopy = {};
            angular.copy(ctrl, ctrlCopy);

            // Get the parent of the form
            var parent = element.parent().controller('form');
            // Remove parent link to the controller
            parent.$removeControl(ctrl);

            // Replace form controller with a "isolated form"
            // var isolatedFormCtrl = {};
            // angular.extend(ctrl, isolatedFormCtrl);
        };
        bestlineIsolateFormLink.setup();
    };

    return bestlineIsolateForm;
});