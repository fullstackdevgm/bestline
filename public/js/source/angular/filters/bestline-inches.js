/**
    * @ngdoc filter
    * @name bestline.filter:bestlineInches
    * @description
    * # bestlineInches
    * Filter in the bestline.
    */
angular.module('bestline').filter('bestlineInches', function() {
    return function(input) {
        input = input || '';
        var inputAsFloat = parseFloat(input);
        if(!isNaN(inputAsFloat)){
            return inputAsFloat.toString();
        }

        return input;
    }
});
