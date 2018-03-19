/**
 * @ngdoc directive
 * @name bestline.directive:bestlineFabricInputRows
 * @description
 * # bestlineFabricInputRows

 Use: <div default-directive="" ></div>
 */
angular.module('bestline')
.directive('bestlineFabricInputRows', function() {
    'use strict';

    var bestlineFabricInputRows = {
        restrict: 'E',
        controllerAs: 'vm',
        scope:{
            rows: '=orderFabrics',
            orderCompanyId: '=',
            api: '=',
        },
        templateUrl: '/js/source/angular/views/directives/bestline-fabric-input-rows.html',
    };

    bestlineFabricInputRows.controller = function($scope, $q, $timeout, filterFilter, bestlineApi){

        var vm = this;
        var bestlineFabricInputRowsController = {};

        bestlineFabricInputRowsController.setup = function(){
            $scope.fabrics = {
                company: {},
            };
            $scope.types = [];
            $scope.api = $scope.api || {};
            $scope.rows = $scope.rows || [];

            //Initiate rows if they exist on load
            if($scope.rows[0]){

                bestlineFabricInputRowsController.initiateRows();
            }
            bestlineFabricInputRowsController.setupExternalApi();
            bestlineFabricInputRowsController.viewApi();
        };
        function FabricRow(){
            this.fabric_type_id = null;
            this.fabric_type_type = null; //string: this is the type string in the fabric types table. If this is set it will override the fabric_type_id on load
            this.fabric_id = null; //pass in a fabric id to load it
            this.canDelete = true; //pass in false to remove ability to delete a row
            this.typeSelect = {
                disabled: false,
                selected: null,
                loading: false,
            };
            this.fabricSelect = {
                showOptions: false,
                selected: {
                    name: 'Select Fabric...',
                },
                options: [],
                loading: false,
            };
            this.optionSettings = {
                type: 'fabric',
            };
        };
        bestlineFabricInputRowsController.initiateRows = function(){

            angular.forEach($scope.rows, setupRow);
            function setupRow(row, index){
                $scope.rows[index] = $.extend(new FabricRow, $scope.rows[index]);

                bestlineFabricInputRowsController.loadRow($scope.rows[index]);
            }
        };
        bestlineFabricInputRowsController.setupExternalApi = function(){

            $scope.api.changeCompanyId = function(companyId){

                $timeout(function(){
                    angular.forEach($scope.rows, loadFabricSelectFabrics)
                    function loadFabricSelectFabrics(row, index){

                        bestlineFabricInputRowsController.getFabricSelectOptions($scope.rows[index]).then(setFabricSelectFabrics);
                        function setFabricSelectFabrics(fabricSelectFabrics){

                            $scope.rows[index].fabricSelect.options = fabricSelectFabrics;
                        }
                    }
                });
            };
            $scope.api.newRow = function(options){

                var newRow =  $.extend(new FabricRow, options);
                $scope.rows.push(newRow);
                bestlineFabricInputRowsController.loadRow(newRow);
            };
            $scope.api.initiateFabrics = function(){
                bestlineFabricInputRowsController.initiateRows();
            };
        };
        bestlineFabricInputRowsController.loadRow = function(row){

            //load fabric options if they exist
            if(row.options && row.options.length > 0){
                row.optionApi.loadOptions();
            }

            //load type options
            if($scope.types.length === 0){
                row.typeSelect.loading = true;
                bestlineFabricInputRowsController.getFabricTypes(loadFabricSelectOptions);
            } else {
                loadFabricSelectOptions($scope.types);
            }
            function loadFabricSelectOptions(fabricTypes){
                row.typeSelect.loading = false;

                if(row.fabric_type_type){
                    row.fabric_type_id = filterFilter($scope.types, {type: row.fabric_type_type}, true)[0].id;
                }

                row.fabricSelect.loading = true;
                bestlineFabricInputRowsController.getFabricSelectOptions(row).then(setFabricSelectOptions);
            }
            function setFabricSelectOptions(fabricOptions){

               row.fabricSelect.options = fabricOptions;
               row.fabricSelect.loading = false;
               if(row.fabric_id){
                    var initialFabric = filterFilter(fabricOptions, {id: row.fabric_id}, true)[0];

                    if(initialFabric){
                        row.fabricSelect.selected = initialFabric;
                    }
               }
            }
        };
        bestlineFabricInputRowsController.checkForFabricTypeFabrics = function(row){

            return $q(checkForFabricTypeFabricsPromise);

            function checkForFabricTypeFabricsPromise(resolveCFFOP, rejectCFFOP){
                if(row.fabric_type_id){
                    if(!$scope.fabrics[row.fabric_type_id]){

                        bestlineFabricInputRowsController.getFabricType(row).then(resolveCFFOP);
                    } else {
                        resolveCFFOP($scope.fabrics[row.fabric_type_id]);
                    }
                } else {
                    resolveCFFOP([]);
                }
            }
        };
        bestlineFabricInputRowsController.checkForComFabrics = function(companyId){

            return $q(checkForComFabricsPromise);

            function checkForComFabricsPromise(resolveCFCFOP, rejectCFCFOP){
                if(!$scope.fabrics.company[companyId]){

                    bestlineFabricInputRowsController.getComFabrics(companyId).then(resolveCFCFOP);
                } else {
                    resolveCFCFOP($scope.fabrics.company[companyId]);
                }
            }
        };
        bestlineFabricInputRowsController.getFabricType = function(orderFabric){

            return $q(getFabricTypePromise);

            function getFabricTypePromise(resolveGetFabricType, rejectGetFabricType){

                orderFabric.fabricSelect.loading = true;
                bestlineApi.fabric().type(orderFabric.fabric_type_id).then(getFabricTypeSuccess, getFabricTypeError);

                function getFabricTypeSuccess(response){

                    orderFabric.fabricSelect.loading = false;
                    $scope.fabrics[orderFabric.fabric_type_id] = response.data;
                    resolveGetFabricType(response.data);
                };
                function getFabricTypeError(response){
                    orderFabric.fabricSelect.loading = false;
                    rejectGetFabricType(response);
                };
            };
        };
        bestlineFabricInputRowsController.getFabricTypes = function(callback){

            var getFabricTypes = {};
            getFabricTypes.setup = function(){

                bestlineApi.fabric().types().then(getFabricTypes.success, getFabricTypes.failure);
            };
            getFabricTypes.success = function(response){

                $scope.types = response.data;

                if(typeof callback === "function"){
                    callback(response.data);
                }

                getFabricTypes.finish();
            };
            getFabricTypes.failure = function(response){

                getFabricTypes.finish();
            };
            getFabricTypes.finish = function(){};
            getFabricTypes.setup();
        };
        bestlineFabricInputRowsController.getComFabrics = function(companyId){

            return $q(getComFabricsPromise);

            function getComFabricsPromise(resolveGetComFabrics, rejectGetComFabrics){
                bestlineApi.company(companyId).fabrics().then(getComFabricsSuccess, rejectGetComFabrics);

                function getComFabricsSuccess(response){
                    $scope.fabrics.company[companyId] = response.data;
                    resolveGetComFabrics(response.data);
                };
            };
        };
        bestlineFabricInputRowsController.hideAllOptions = function(rows){

            angular.forEach(rows, hideRowOptions);
            function hideRowOptions(row, index){
                row.fabricSelect.showOptions = false;
            };
        };
        bestlineFabricInputRowsController.viewApi = function(){

            $scope.fabricSelectFocus = function($event, fabricSelect){

                $event.preventDefault();
                var target = angular.element($event.target);

                if(fabricSelect.supressFocusEvent) {
                    fabricSelect.supressFocusEvent = undefined;
                } else {
                   showSelectOptions(target, fabricSelect);
                }
            };

            $scope.fabricSelectClick = function($event, fabricSelect){

                $event.preventDefault();
                var target = angular.element($event.target);

                if(fabricSelect.showOptions){
                    bestlineFabricInputRowsController.hideAllOptions($scope.rows);
                } else {
                    showSelectOptions(target, fabricSelect);
                }
            };

            $scope.fabricSelectKeydown = function($event, fabricSelect){

                $event.preventDefault();

                var target = angular.element($event.target);
                showSelectOptions(target, fabricSelect);
            };

            function showSelectOptions(target, fabricSelect){

                var customSelect = target.closest('.jsCustomSelect');
                var search = customSelect.find('.jsSearch');
                bestlineFabricInputRowsController.hideAllOptions($scope.rows);
                fabricSelect.showOptions = true;

                $timeout(function(){
                    search.focus();
                });
            };

            $scope.fabricSearchKeydown = function($event, fabricSelect){

                if($event.which === 40){
                    var me = angular.element($event.target);
                    var parent = me.closest('.jsCustomSelect');
                    var firstOption = parent.find('.jsOptions > .jsItem:eq(0)');

                    bestlineFabricInputRowsController.hideAllOptions($scope.rows);
                    fabricSelect.showOptions = true;

                    if(fabricSelect.selected.element){
                        $timeout(function(){
                            firstOption.focus();
                            fabricSelect.selected.element.focus();
                        });
                    } else {

                        $timeout(function(){
                            firstOption.focus();
                        });
                    }
                }
            };

            $scope.fabricSearchBlur = function($event, fabricSelect){

                $event.preventDefault();
                var target = angular.element($event.target);
                var parent = target.closest('.jsCustomSelect');

                if($event.relatedTarget){
                    var relatedTarget = angular.element($event.relatedTarget);
                    var isChild = $.contains(parent.get(0), $event.relatedTarget);

                    if(!isChild){
                        hideSelectOptions();
                    }
                } else {
                    hideSelectOptions();
                }

                function hideSelectOptions(){
                    var selectName = parent.find('.jsSelectName');
                    fabricSelect.showOptions = false;
                    fabricSelect.supressFocusEvent = true;

                    $timeout(function(){
                        selectName.focus();
                    });
                }
            };

            $scope.fabricOptionClick = function($event, orderFabric, fabric){

                $event.preventDefault();
                setFabricOption($event, orderFabric, fabric);
            };

            $scope.fabricOptionKeydown = function($event, orderFabric, fabric){

                $event.preventDefault();

                //for enter
                if($event.which === 13){
                    setFabricOption($event, orderFabric, fabric);
                }

                //for down arrow
                if($event.which === 40){
                    if($event.target.nextElementSibling){
                        $event.target.nextElementSibling.focus();
                    }
                }

                //for up arrow
                if($event.which === 38){
                    if($event.target.previousElementSibling){
                        $event.target.previousElementSibling.focus();
                    }
                }
            };

            function setFabricOption($event, orderFabric, fabric){

                var target = angular.element($event.target);
                var customSelect = target.closest('.jsCustomSelect');
                var selectName = customSelect.find('.jsSelectName');

                if(fabric.related_option_id){
                    orderFabric.optionApi.newOption({option_id: fabric.related_option_id});
                } else if(orderFabric.type && (orderFabric.type.type === 'embellishment' || orderFabric.type.type === 'interlining')) {
                    orderFabric.optionApi.newOption({filterSlug: orderFabric.type.type});
                }

                fabric.element = target;
                orderFabric.fabricSelect.selected = fabric;
                orderFabric.fabric_id = fabric.id;
                bestlineFabricInputRowsController.hideAllOptions($scope.rows);
                orderFabric.fabricSelect.supressFocusEvent = true;

                $timeout(function(){
                    selectName.focus();
                });
            };

            $scope.typeSelectChange = function(row){

                row.type = filterFilter($scope.types, {id: row.fabric_type_id}, true)[0];
                row.fabric_id = null;
                row.fabricSelect.selected = {
                    name: 'Select fabric...',
                }

                bestlineFabricInputRowsController.getFabricSelectOptions(row).then(onFabricsLoaded);
                function onFabricsLoaded(fabricSelectOptions){
                    row.fabricSelect.options = fabricSelectOptions;
                };
            };

            $scope.deleteRow = function(row){

                var hasOrderFabricId = typeof row.id !== 'undefined';
                var hasOrderId = typeof row.order_id !== 'undefined';

                if(hasOrderId && hasOrderFabricId) {

                    row.deleting = true;
                    bestlineApi.order(row.order_id).fabric(row.id).destroy().then(deleteOrderFabricSuccess, deleteOrderFabricError);
                } else {
                    removeFromView();
                }

                function deleteOrderFabricSuccess(response){
                    row.deleting = undefined;
                    removeFromView();
                };
                function deleteOrderFabricError(response){
                    row.deleting = undefined;
                };
                function removeFromView(){
                    var index = $scope.rows.indexOf(row);
                    $scope.rows.splice(index, 1);
                };
            };

            $scope.addOption = function(row){
                row.options.push({editing: true});
                row.optionApi.loadOptions();
            };
        };
        bestlineFabricInputRowsController.getFabricSelectOptions = function(row){

            return $q(getFabricSelectOptionsPromise);

            function getFabricSelectOptionsPromise(getFabricSelectOptionsResolve, getFabricSelectOptionsReject){

                bestlineFabricInputRowsController.checkForFabricTypeFabrics(row).then(checkForComFabrics);
                function checkForComFabrics(fabricTypeFabrics){

                    if($scope.orderCompanyId){
                        bestlineFabricInputRowsController.checkForComFabrics($scope.orderCompanyId).then(mergeComAndTypeFabrics);
                    } else {
                        getFabricSelectOptionsResolve(fabricTypeFabrics);
                    }
                    function mergeComAndTypeFabrics(comFabrics){

                        var mergedFabrics = comFabrics.concat(fabricTypeFabrics);
                        getFabricSelectOptionsResolve(mergedFabrics);
                    }
                }
            }
        };
        bestlineFabricInputRowsController.setup();
    };

    return bestlineFabricInputRows;
});
