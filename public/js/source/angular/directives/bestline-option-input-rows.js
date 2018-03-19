/**
 * @ngdoc directive
 * @name bestline.directive:bestlineOptionInputRows
 * @description
 * # bestlineOptionInputRows

 Use: <bestline-option-input-rows order-options="" api="" settings=""></bestline-option-input-rows>
 */
angular.module('bestline')
.directive('bestlineOptionInputRows', function() {
    'use strict';

    var bestlineOptionInputRows = {
        restrict: 'E',
        controllerAs: 'vmOptionRows',
        scope:{
            settings: '=',
            orderOptions: '=',
            api: '=',
        },
        templateUrl: '/js/source/angular/views/directives/bestline-option-input-rows.html',
    };

    bestlineOptionInputRows.controller = function($scope, $q, filterFilter, bestlineApi){

        var vm = this;
        var bestlineOptionInputRowsController = {};

        bestlineOptionInputRowsController.setup = function(){
            $scope.options = [];
            $scope.subOptions = [];
            $scope.api = $scope.api || {};
            $scope.orderOptions = $scope.orderOptions || [];
            $scope.optionDataArray = [
                {
                    key: 'size',
                    name: 'Size',
                },
                {
                    key: 'size_bottom',
                    name: 'Bottom',
                },
                {
                    key: 'size_sides',
                    name: 'Sides',
                },
                {
                    key: 'size_top',
                    name: 'Top',
                },
                {
                    key: 'inset_size_sides',
                    name: 'Inset Sides',
                },
                {
                    key: 'inset_size_bottom',
                    name: 'Inset Bottom',
                },
                {
                    key: 'inset_size_top',
                    name: 'Inset Top',
                },
                {
                    key: 'number',
                    name: 'Number',
                },
                {
                    key: 'degrees',
                    name: 'Degrees',
                },
                {
                    key: 'assembler_note',
                    name: 'Assembler Note',
                },
            ];

            bestlineOptionInputRowsController.getOptionOptions();
            bestlineOptionInputRowsController.setupExternalApi();
            bestlineOptionInputRowsController.viewApi();
        };
        function OptionRow(){
            this.option_id = null;
            this.load_option_name = null; //add name to search for name on load
            this.sub_option_id = null;
            this.load_sub_option_name = null; //add name to search for name on load
            this.optionSelect = {
                loading: false,
            };
            this.subOptionSelect = {
                options: [],
                loading: false,
            };
            this.filterSlug = null; // string 'embellishment' or 'interlining' to filter by those option types
        };
        function OptionSettings(){
            this.type = 'order'; //can be 'order' for order options, 'fabric' for fabric options, or 'line' for orderLine options
        };
        bestlineOptionInputRowsController.initiateRows = function(){

            $scope.settings = $.extend(new OptionSettings, $scope.settings);
            var allRowPromises = [];

            angular.forEach($scope.orderOptions, setupRow);
            function setupRow(row, index){
                $scope.orderOptions[index] = $.extend(new OptionRow, $scope.orderOptions[index]);

                var rowPromise = bestlineOptionInputRowsController.loadOptions($scope.orderOptions[index]);
                allRowPromises.push(rowPromise);
            }

            return $q.all(allRowPromises);
        };
        bestlineOptionInputRowsController.loadOptions = function(orderOption){

            return $q(loadOptionPromise);

            function loadOptionPromise(loadOptionResolve, loadOptionReject){
                if($scope.options.length === 0){
                    orderOption.optionSelect.loading = true;
                    bestlineOptionInputRowsController.getOptionOptions().then(onOptionOptionsLoaded);
                } else {
                    onOptionOptionsLoaded($scope.options);
                }
                function onOptionOptionsLoaded(optionOptions){

                    loadOptionResolve(optionOptions);

                    orderOption.optionSelect.loading = false;

                    if(orderOption.option_id){

                        var selectedOption = filterFilter($scope.options, {id: orderOption.option_id}, true)[0];
                        loadSubOptions(selectedOption);
                    }
                    if(orderOption.load_option_name){

                        var selectedOption = filterFilter($scope.options, {name: orderOption.load_option_name}, true)[0];

                        if(selectedOption){
                            orderOption.option_id = selectedOption.id;
                            loadSubOptions(selectedOption);
                        }
                    }
                    function loadSubOptions(selectedOption){
                        orderOption.option = selectedOption;

                        bestlineOptionInputRowsController.loadSubOptions(orderOption);
                    }
                }
            }
        };
        bestlineOptionInputRowsController.loadSubOptions = function(orderOption){

            return $q(loadSubOptionsPromise);

            function loadSubOptionsPromise(loadSubOptionsResolve, loadSubOptionsReject){

                orderOption.subOptionSelect.loading = true;

                if(!$scope.subOptions[orderOption.option_id]){
                    bestlineOptionInputRowsController.getSubOptions(orderOption.option_id).then(onSubOptionsLoaded);
                } else {
                    onSubOptionsLoaded($scope.subOptions[orderOption.option_id]);
                }
                function onSubOptionsLoaded(subOptions){

                    loadSubOptionsResolve(subOptions);

                    orderOption.subOptionSelect.options = subOptions;
                    orderOption.subOptionSelect.loading = false;

                    if(orderOption.sub_option_id){
                        setSubOption(orderOption);
                    } else if(orderOption.load_sub_option_name){
                        var selectedSubOption = filterFilter(orderOption.subOptionSelect.options, {name: orderOption.load_sub_option_name}, true)[0];
                        if(selectedSubOption){
                            orderOption.sub_option_id = selectedSubOption.id;
                            setSubOption(orderOption);
                        }
                    } else if(subOptions.length === 1){

                        orderOption.sub_option_id = orderOption.subOptionSelect.options[0].id
                        setSubOption(orderOption);
                    }
                }
            }
        };
        bestlineOptionInputRowsController.setupExternalApi = function(){

            $scope.api.loadOptions = function(){

                return $q(apiLoadOptionsPromise);

                function apiLoadOptionsPromise(apiLoadOptionsResolve, apiLoadOptionsReject){
                    bestlineOptionInputRowsController.initiateRows().then(apiLoadOptionsResolve, apiLoadOptionsReject);
                }
            };
            $scope.api.newOption = function(newOrderOption){

                var initializedOption = $.extend(new OptionRow, newOrderOption);
                $scope.orderOptions.push(initializedOption);
                bestlineOptionInputRowsController.loadOptions(initializedOption);
            };
        };
        bestlineOptionInputRowsController.viewApi = function(){

            $scope.optionSelectChange = function(orderOption){

                if(orderOption.option_id){

                    var selectedOption = filterFilter($scope.options, {id: orderOption.option_id}, true)[0];
                    orderOption.option = selectedOption;

                    bestlineOptionInputRowsController.loadSubOptions(orderOption);
                }
            };

            $scope.subOptionSelectChange = function(orderOption){

                setSubOption(orderOption);
            };

            $scope.deleteOption = function(option){

                var hasOptionId = typeof option.id !== 'undefined';

                if(hasOptionId) {

                    option.deleting = true;
                    if($scope.settings.type === 'order'){

                        bestlineApi.order().option(option.id).destroy().then(deleteOptionSuccess, deleteOptionError);
                    } else if($scope.settings.type === 'fabric'){

                        bestlineApi.order().fabric().option(option.id).destroy().then(deleteOptionSuccess, deleteOptionError);
                    } else if($scope.settings.type === 'line'){

                        bestlineApi.order().orderLine().option(option.id).destroy().then(deleteOptionSuccess, deleteOptionError);
                    } else {
                        deleteOptionError();
                    }
                } else {
                    removeFromView();
                }

                function deleteOptionSuccess(response){
                    option.deleting = undefined;
                    removeFromView();
                };
                function deleteOptionError(response){
                    option.deleting = undefined;
                };
                function removeFromView(){
                    var index = $scope.orderOptions.indexOf(option);
                    $scope.orderOptions.splice(index, 1);
                    $scope.$emit('order-option-deleted', option);
                };
            };

            vm.optionFilter = function(orderOption){
                return function(option){

                    if(!orderOption.filterSlug){
                        return option;
                    } else if(orderOption.filterSlug === 'interlining' && option.is_interlining_option){
                        return option;
                    } else if(orderOption.filterSlug === 'embellishment' && option.is_embellishment_option){
                        return option;
                    }
                }
            }
        };
        bestlineOptionInputRowsController.getSubOptions = function(optionId){

            return $q(getSubOptionsPromise);

            function getSubOptionsPromise(resolveGetSubOptions, rejectGetSubOptions){

                bestlineApi.option().children(optionId).then(getSubOptionsSuccess, rejectGetSubOptions);

                function getSubOptionsSuccess(response){
                    $scope.subOptions[optionId] = response.data.sub_options;

                    resolveGetSubOptions(response.data.sub_options);
                };
            };
        };
        bestlineOptionInputRowsController.getOptionOptions = function(){

            return $q(getOptionOptionsPromise);

            function getOptionOptionsPromise(resolveGetOptionOptions, rejectGetOptionOptions){
                bestlineApi.option().options().then(getOptionOptionsSuccess, rejectGetOptionOptions);

                function getOptionOptionsSuccess(response){
                    $scope.options = response.data;
                    resolveGetOptionOptions(response.data);
                };
            };
        };
        function setSubOption(orderOption){
            var selectedSubOption = filterFilter(orderOption.subOptionSelect.options, {id: orderOption.sub_option_id}, true)[0];
            orderOption.sub_option = selectedSubOption;

            //show correct option data fields
            if(selectedSubOption && selectedSubOption.data){
                selectedSubOption.data.id = (orderOption.data && orderOption.data.id)? orderOption.data.id : undefined; //we don't want this to update

                angular.forEach($scope.optionDataArray, eachDataOption);
                function eachDataOption(data){
                    if(selectedSubOption.data[data.key]){
                        selectedSubOption.data[data.key] = " ";
                        selectedSubOption.data['show_'+ data.key] = true;
                    }
                }
            }

            orderOption.data = $.extend(selectedSubOption.data, orderOption.data);

            //alert controller that an embellishment is needed
            if(selectedSubOption && selectedSubOption.is_embellishment_option === 1){
                $scope.$emit('option-needs-embellishment', orderOption);
            }

            if(orderOption.option_id && orderOption.sub_option_id){
                $scope.$emit('sub-option-changed', orderOption);
            }
        }

        bestlineOptionInputRowsController.setup();
    };

    return bestlineOptionInputRows;
});
