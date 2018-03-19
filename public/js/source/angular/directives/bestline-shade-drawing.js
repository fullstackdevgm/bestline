/**
 * @ngdoc directive
 * @name bestline.directive:bestlineShadeDrawing
 * @description
 * # bestlineShadeDrawing

 Use: <bestline-shade-drawing></bestline-shade-drawing>
 */
angular.module('bestline')
.directive('bestlineShadeDrawing', function($timeout) {
    'use strict';

    var bestlineShadeDrawing = {
        restrict: 'E',
        scope:{
            orderLine: "=",
            panZoom: "="
        },
        templateUrl: '/js/source/angular/views/directives/bestline-shade-drawing.html',
    };

    bestlineShadeDrawing.controller = function($scope){

        var vm = this;

        var buildShadeDrawing = {};
        buildShadeDrawing.setup = function(){

            $scope.panZoom = {}; //set by directive bestline-svg-pan-zoom

            $scope.shade = new Shade;
            buildShadeDrawing.buildLayout();
            buildShadeDrawing.buildGeneralShapes();
            buildShadeDrawing.buildRings();
            buildShadeDrawing.buildColumnShapes();
            buildShadeDrawing.buildColumnDividers();
            buildShadeDrawing.buildPanels();
            buildShadeDrawing.buildMeasurements();
            buildShadeDrawing.shadeFilters();
            buildShadeDrawing.viewApi();
        };
        function Shade(){

            var shade = this;
            shade.hasShade = $scope.orderLine.has_shade;
            shade.measurements = [];
            shade.columns = [];
            shade.measurements = [];
            shade.columns = [];
            shade.shapeDividers = [];
            shade.rings = [];
            shade.panels = [];
            shade.width =  Number($scope.orderLine.manufacturing_width);
            shade.height = Number($scope.orderLine.manufacturing_length);
            shade.spacing = 1.5; //in inches
            shade.shape =  $scope.orderLine.product.shape;
            shade.isTrapezoid = (shade.shape === "Square/Trapezoid")? true : false;
            shade.isSquare = (shade.shape === 'Square' || shade.shape === 'Square/Trapezoid' || shade.shape === 'TDBU/BU')? true : false;
            shade.isCloud = (shade.shape === 'SCloud' || shade.shape === 'PCloud')? true : false;
            shade.isDogear = (shade.shape === 'Dog Ear')? true : false;
            shade.isRoundedBottom = (shade.shape === 'Austrian' || shade.shape === 'Balloon' || shade.shape === 'SCloud' || shade.shape === 'PCloud' || shade.shape === 'Dog Ear')? true : false;
            shade.trapezoidTriangleWidth =  (shade.isTrapezoid) ? shade.width * 0.08 : 0;
            shade.headerboard = Number($scope.orderLine.manufacturing_headerboard);
            shade.lineWidth = shade.spacing * .15;
            shade.dashedDasharray = "0, " + shade.lineWidth * 2;
            shade.embellishmentOption = $scope.orderLine.embellishment_option;
            shade.embellishmentSizing = {};
            shade.embellishmentSizing.size_bottom = Number(getPath($scope,'orderLine.embellishment_option.data.size_bottom', 0));
            shade.embellishmentSizing.inset_size_bottom = Number(getPath($scope,'orderLine.embellishment_option.data.inset_size_bottom', 0));
            shade.embellishmentSizing.size_sides = Number(getPath($scope,'orderLine.embellishment_option.data.size_sides', 0));
            shade.embellishmentSizing.inset_size_sides = Number(getPath($scope,'orderLine.embellishment_option.data.inset_size_sides', 0));
            shade.embellishmentSizing.size_top = Number(getPath($scope,'orderLine.embellishment_option.data.size_top', 0));
            shade.embellishmentSizing.inset_size_top = Number(getPath($scope,'orderLine.embellishment_option.data.inset_size_top', 0));
            shade.valanceHeight = Number($scope.orderLine.valance_height);
            shade.valanceWidth = Number($scope.orderLine.valance_width);
            shade.valanceReturn = Number($scope.orderLine.valance_return);
            shade.valanceHeaderboard = Number($scope.orderLine.manufacturing_valance_headerboard);
            shade.hasValance = $scope.orderLine.has_valance;
        };
        buildShadeDrawing.buildLayout = function(){

            var shade = $scope.shade;
            var orderLine = $scope.orderLine;
            var ringSpacing = Number($scope.orderLine.ring_spacing);

            shade.layout = {};
            buildRows();
            buildColumns();

            function buildRows(){
                shade.layout.rows = {
                    valanceMeasurement: {},
                    valance: {},
                    shadeMeasurement: {},
                    headerboard: {},
                    shape: {
                        rows: {
                            skirt: {},
                            panelMeasurement: {},
                        },
                    },
                    bottomMeasurement: {},
                    bottom: {},
                };

                var valanceHeaderboard = {
                    y: shade.spacing*2,
                    height: shade.valanceHeaderboard,
                    marginBottom: shade.spacing*2,
                };
                shade.layout.rows.valanceHeaderboard = valanceHeaderboard;

                var valanceMeasurement = {
                    y: valanceHeaderboard.y + valanceHeaderboard.height + valanceHeaderboard.marginBottom,
                    height: shade.spacing,
                    marginBottom: 0,
                };
                shade.layout.rows.valanceMeasurement = valanceMeasurement;

                var valance = {
                    y: valanceMeasurement.y + valanceMeasurement.height + valanceMeasurement.marginBottom,
                    height: shade.valanceHeight,
                    marginBottom: shade.spacing*1.5,
                };
                shade.layout.rows.valance = valance;

                var headerboard = {
                    y: valance.y + valance.height + valance.marginBottom,
                    height: shade.headerboard,
                    marginBottom: shade.spacing*2,
                };
                shade.layout.rows.headerboard = headerboard;

                var shadeMeasurement = {
                    y: headerboard.y + headerboard.height + headerboard.marginBottom,
                    height: shade.spacing,
                    marginBottom: 0,
                };
                shade.layout.rows.shadeMeasurement = shadeMeasurement;

                var shape = {
                    y: shadeMeasurement.y + shadeMeasurement.height + shadeMeasurement.marginBottom,
                    height: shade.height,
                    marginBottom: shade.spacing,
                    rows: {},
                };
                shade.layout.rows.shape = shape;

                var embellishmentTop = {
                    y: shape.y + shade.embellishmentSizing.inset_size_top,
                }
                shade.layout.rows.shape.rows.embellishmentTop = embellishmentTop;

                var panelMeasurement = {
                    y: shape.y + orderLine.panel_height,
                };
                shade.layout.rows.shape.rows.panelMeasurement = panelMeasurement;

                var skirt = {
                    y: shape.y + orderLine.panel_height * orderLine.total_panels,
                    height: orderLine.skirt_height,
                    embellishment: {},
                    inset: {},
                };
                shade.layout.rows.shape.rows.skirt = skirt;

                var skirtEmbellishment = {
                    y: skirt.y + skirt.height - shade.embellishmentSizing.inset_size_bottom - shade.embellishmentSizing.size_bottom,
                    height: shade.embellishmentSizing.size_bottom,
                };
                shade.layout.rows.shape.rows.skirt.embellishment = skirtEmbellishment;

                var embellishmentSkirtInset = {
                    y: skirtEmbellishment.y + skirtEmbellishment.height,
                };
                shade.layout.rows.shape.rows.skirt.inset = embellishmentSkirtInset;

                var bottomMeasurement = {
                    y: shape.y + shape.height + shape.marginBottom + shade.spacing,
                    height: shade.spacing,
                }
                shade.layout.rows.bottomMeasurement = bottomMeasurement;

                var bottom = {
                    y: shade.layout.rows.bottomMeasurement.y + shade.layout.rows.bottomMeasurement.height,
                };
                shade.layout.rows.bottom = bottom;
            };
            function buildColumns(){

                shade.layout.columns = {};

                var viewboxLeft = {
                    x: 0,
                    width: shade.spacing,
                    marginRight: shade.spacing,
                };
                shade.layout.columns.viewboxLeft = viewboxLeft;

                var shapeBottomLeft = {
                    x: viewboxLeft.x + viewboxLeft.width + viewboxLeft.marginRight,
                    width: shade.width + shade.trapezoidTriangleWidth * 2,
                    marginRight: shade.spacing,
                };
                shade.layout.columns.shapeBottomLeft = shapeBottomLeft;

                var shapeTopLeft = { //can be different from bottom left with trapezoid
                    x: shapeBottomLeft.x + shade.trapezoidTriangleWidth,
                    width: shade.width,
                    columns: {
                        firstRing: {
                            x: null,
                        }
                    },
                };
                if(shade.isDogear){
                    shapeTopLeft.columns.firstRing.x = shapeTopLeft.x + ringSpacing / 2;
                } else {
                    shapeTopLeft.columns.firstRing.x = shapeTopLeft.x + Number(orderLine.product.ring_from_edge);
                }
                shade.layout.columns.shapeTopLeft = shapeTopLeft;

                var valanceTopLeft = {
                    x: shapeTopLeft.x,
                    width: shade.valanceWidth,
                }
                shade.layout.columns.valanceTopLeft = valanceTopLeft;

                var embellishmentSidesLeft = {
                    x: shapeTopLeft.x + shade.embellishmentSizing.inset_size_sides,
                }
                shade.layout.columns.embellishmentSidesLeft = embellishmentSidesLeft;

                var shapeTopRight = {
                    x: shapeTopLeft.x + shapeTopLeft.width,
                    width: 0,
                };
                shade.layout.columns.shapeTopRight = shapeTopRight;

                var embellishmentSidesRight = {
                    x: shapeTopRight.x - shade.embellishmentSizing.inset_size_sides - shade.embellishmentSizing.size_sides,
                }
                shade.layout.columns.embellishmentSidesRight = embellishmentSidesRight;

                var valanceTopRight = {
                    x: valanceTopLeft.x + valanceTopLeft.width,
                }
                shade.layout.columns.valanceTopRight = valanceTopRight;

                var valanceLabel = {
                    x: valanceTopRight.x + shade.valanceReturn,
                    width: shade.spacing * 4,
                };
                shade.layout.columns.valanceLabel = valanceLabel;

                var panelMeasurement = {
                    x: shapeBottomLeft.x + shapeBottomLeft.width + shapeBottomLeft.marginRight,
                    width: shade.spacing,
                    marginRight: shade.spacing * 3,
                };
                shade.layout.columns.panelMeasurement = panelMeasurement;

                var embellishmentMeasurement = {
                    x: panelMeasurement.x + panelMeasurement.width + panelMeasurement.marginRight,
                    width: shade.spacing,
                };
                shade.layout.columns.embellishmentMeasurement = embellishmentMeasurement;

                var viewBoxRight = {
                    x: Math.max(embellishmentMeasurement.x,valanceLabel.x + valanceLabel.width) + embellishmentMeasurement.width,
                };
                shade.layout.columns.viewBoxRight = viewBoxRight;
            };
        };
        buildShadeDrawing.buildGeneralShapes = function(){

            var orderLine = $scope.orderLine;
            $scope.shade.drawing = {};

            buildValance();
            buildValanceHeaderboard();
            buildHeaderboard();
            buildTrapezoid();
            buildShadeSquare();
            buildTopLine();
            buildCloudLine();
            buildPcloudMiddleLine();
            buildEmbellishment();

            function buildValance(){
                var valance = {
                    x: $scope.shade.layout.columns.shapeTopLeft.x,
                    y: $scope.shade.layout.rows.valance.y,
                    height: $scope.shade.valanceHeight,
                    width: Number(orderLine.valance_width),
                };
                var valanceReturn = {
                    x1: valance.x + valance.width,
                    y1: valance.y,
                    x2: valance.x + valance.width + $scope.shade.valanceReturn,
                    y2: valance.y + $scope.shade.spacing,
                    x3: valance.x + valance.width + $scope.shade.valanceReturn,
                    y3: valance.y + valance.height + $scope.shade.spacing,
                    x4: valance.x + valance.width,
                    y4: valance.y + valance.height,
                };
                valance.return = valanceReturn;
                $scope.shade.drawing.valance = valance;
            }
            function buildValanceHeaderboard(){

                var valanceHeaderboard = {
                    x: $scope.shade.layout.columns.shapeTopLeft.x,
                    y: $scope.shade.layout.rows.valanceHeaderboard.y,
                    height: $scope.shade.valanceHeaderboard,
                    width: $scope.shade.width,
                };
                $scope.shade.drawing.valanceHeaderboard = valanceHeaderboard;
            }
            function buildHeaderboard(){

                var headerboard = {
                    x: $scope.shade.layout.columns.shapeTopLeft.x,
                    y: $scope.shade.layout.rows.headerboard.y,
                    height: $scope.shade.headerboard,
                    width: $scope.shade.width,
                };
                $scope.shade.drawing.headerboard = headerboard;
            }
            function buildTrapezoid(){
                var trapezoid = {}
                trapezoid.x1 = $scope.shade.layout.columns.shapeBottomLeft.x + $scope.shade.trapezoidTriangleWidth;
                trapezoid.y1 = $scope.shade.layout.rows.shape.y;
                trapezoid.x2 = trapezoid.x1 + $scope.shade.width;
                trapezoid.y2 = $scope.shade.layout.rows.shape.y;
                trapezoid.x3 = trapezoid.x2 + $scope.shade.trapezoidTriangleWidth;
                trapezoid.y3 = trapezoid.y2 + $scope.shade.height;
                trapezoid.x4 = $scope.shade.layout.columns.shapeBottomLeft.x;
                trapezoid.y4 = trapezoid.y1 + $scope.shade.height;
                $scope.shade.drawing.trapezoid = trapezoid;
            }
            function buildShadeSquare(){
                //build square
                var square = {};
                square = {};
                square.x = $scope.shade.layout.columns.shapeBottomLeft.x;
                square.y = $scope.shade.layout.rows.shape.y;
                square.width = $scope.shade.width;
                square.height = $scope.shade.height;
                $scope.shade.drawing.square = square;
            }
            function buildTopLine(){
                //top line for most shapes except pcloud, square, and trapezoid
                $scope.shade.drawing.topLine = {
                    x1: $scope.shade.layout.columns.shapeTopLeft.x,
                    y1: $scope.shade.layout.rows.shape.y,
                    x2: $scope.shade.layout.columns.shapeTopLeft.x + $scope.shade.width,
                    y2: $scope.shade.layout.rows.shape.y,
                    show: $scope.shade.shape !== 'PCloud',
                };
            }
            function buildCloudLine(){
                //bottom horizontal line for cloud designs
                $scope.shade.drawing.cloudHorizontal = {
                    x1: $scope.shade.layout.columns.shapeBottomLeft.x,
                    y1: $scope.shade.layout.rows.shape.y + $scope.shade.spacing,
                    y2: $scope.shade.layout.rows.shape.y + $scope.shade.spacing,
                };
                $scope.shade.drawing.cloudHorizontal.x2 = $scope.shade.drawing.cloudHorizontal.x1 + $scope.shade.width;
            }
            function buildPcloudMiddleLine(){
                //pcloud middle line
                $scope.shade.drawing.pcloudMiddleLine = {
                    x1: $scope.shade.layout.columns.shapeBottomLeft.x,
                    y1: $scope.shade.layout.rows.shape.y + ($scope.shade.spacing * 0.5),
                    y2: $scope.shade.layout.rows.shape.y + ($scope.shade.spacing * 0.5),
                };
                $scope.shade.drawing.pcloudMiddleLine.x2 = $scope.shade.drawing.pcloudMiddleLine.x1 + $scope.shade.width;
            }
            function buildEmbellishment(){

                var embellishmentImage = buildShadeDrawing.getEmbellishmentImage();

                $scope.shade.drawing.embellishment_bottom = {
                    x: $scope.shade.layout.columns.embellishmentSidesLeft.x,
                    y: $scope.shade.layout.rows.shape.rows.skirt.embellishment.y,
                    width: $scope.shade.width - $scope.shade.embellishmentSizing.inset_size_sides * 2,
                    height: $scope.shade.embellishmentSizing.size_bottom,
                    backgroundImage: embellishmentImage,
                };
                $scope.shade.drawing.embellishment_sides_left = {
                    x: $scope.shade.layout.columns.embellishmentSidesLeft.x,
                    y: $scope.shade.layout.rows.shape.rows.embellishmentTop.y,
                    width: $scope.shade.embellishmentSizing.size_sides,
                    height: $scope.shade.height - $scope.shade.embellishmentSizing.inset_size_top - $scope.shade.embellishmentSizing.inset_size_bottom,
                    backgroundImage: embellishmentImage,
                };
                $scope.shade.drawing.embellishment_sides_right = {
                    x: $scope.shade.layout.columns.embellishmentSidesRight.x,
                    y: $scope.shade.layout.rows.shape.rows.embellishmentTop.y,
                    width: $scope.shade.embellishmentSizing.size_sides,
                    height: $scope.shade.height - $scope.shade.embellishmentSizing.inset_size_top - $scope.shade.embellishmentSizing.inset_size_bottom,
                    backgroundImage: embellishmentImage,
                };
            }
        };
        buildShadeDrawing.getEmbellishmentImage = function(){

            var embellishmentOption = $scope.orderLine.embellishment_option;

            if(embellishmentOption && embellishmentOption.order_fabric){
                return '/uploads/fabrics/' + embellishmentOption.order_fabric.fabric.image;
            } else {
                return null;
            }
        };
        buildShadeDrawing.buildRings = function(){

            var orderLine = $scope.orderLine;
            var shade = $scope.shade;
            var layout = $scope.shade.layout;
            var ringSpacing = Number(orderLine.ring_spacing);
            var ringColumns = Number(orderLine.total_ring_columns);
            var skirtHeight = orderLine.skirt_height;

            //set starting x of ring
            var ringX = layout.columns.shapeTopLeft.columns.firstRing.x;
            for(var i = 0; i < ringColumns;i++){

                var isFirst = (i === 0)? true : false;
                var isLast = (i === ringColumns - 1)? true : false;

                var newRingLine = {
                    x: ringX,
                    y: layout.rows.shape.y,
                    height: shade.height - skirtHeight,
                    dasharray: shade.dashedDasharray,
                }

                //extend vertical line of square shaped shades
                if(shade.isSquare){
                    newRingLine.height = newRingLine.height + skirtHeight;
                }

                $scope.shade.rings.push(newRingLine);

                ringX = ringX + ringSpacing;
            }
        };
        buildShadeDrawing.buildColumnShapes = function(){

            var orderLine = $scope.orderLine; //set in view
            var shadeShape = orderLine.product.shape;
            var shade = $scope.shade;
            var layout = $scope.shade.layout;
            var isSquare = $scope.shade.isSquare;
            var isDogear = $scope.shade.isDogear;
            var ringSpacing = Number(orderLine.ring_spacing);
            var ringFromEdge = Number(orderLine.product.ring_from_edge);

            //loop through shapeColumns
            var shapeColumns = (!isDogear)? orderLine.total_ring_columns - 1 : orderLine.total_ring_columns + 1;
            var shapeX = layout.columns.shapeTopLeft.x;
            for(var i = 0; i < shapeColumns;i++){

                var isFirst = (i === 0)? true : false;
                var isLast = (i === shapeColumns - 1)? true : false;
                var shapeColumnWidth = (isFirst || isLast)? ringSpacing + ringFromEdge : ringSpacing;

                var newBaseShape = {
                    shape: orderLine.product.shape,
                    x: shapeX,
                    y: layout.rows.shape.rows.skirt.y,
                    width:shapeColumnWidth,
                    skirtHeight: $scope.orderLine.skirt_height,
                    embellishment: {
                        height: shade.embellishmentSizing.size_bottom,
                    },
                    transform: "", //use to flip the shape
                }

                //shapes need to cover both rings if there is only one column
                if(shapeColumns === 1){
                    shapeColumnWidth = shapeColumnWidth + ringFromEdge;
                    newBaseShape.width = shapeColumnWidth;
                }

                //dogear shades have a different column structure
                if(isDogear){
                    if(isLast || isFirst){
                        newBaseShape.shape = 'doghalf';
                        shapeColumnWidth = (shapeColumnWidth - ringFromEdge) / 2;
                        newBaseShape.width = shapeColumnWidth;
                    }
                    if(isFirst){
                        newBaseShape.transform = "translate("+ newBaseShape.width +", 0) scale(-1,1)";
                    }
                }

                $scope.shade.columns.push(newBaseShape);

                shapeX = shapeX + shapeColumnWidth;
            }
        };
        buildShadeDrawing.buildColumnDividers = function(){

            var orderLine = $scope.orderLine;
            var shade = $scope.shade;
            var isSquare = $scope.shade.isSquare;
            var isDogear = $scope.shade.isDogear;
            var layout = $scope.shade.layout;
            var ringSpacing = Number(orderLine.ring_spacing);
            var ringFromEdge = Number(orderLine.product.ring_from_edge);

            //loop through dividerColumns
            var dividerColumns = (!isDogear)? orderLine.total_ring_columns : orderLine.total_ring_columns + 2;
            var dividerX = layout.columns.shapeTopLeft.x;
            for(var i = 0; i < dividerColumns;i++){

                var isFirst = (i === 0)? true : false;
                var isSecondToLast = (i === dividerColumns - 2)? true : false;
                var isLast = (i === dividerColumns - 1)? true : false;
                var shapeColumnWidth = (isFirst || isSecondToLast)? ringSpacing + ringFromEdge : ringSpacing;

                var newShapeDivider = {
                    x: dividerX,
                    y: layout.rows.shape.y,
                    height: shade.height - orderLine.skirt_height,
                    shape: orderLine.product.shape,
                    dontShow: 'none', //can be left or right (only used for cloud design)
                }

                //change height of first line if dogear
                if(isDogear){
                    if(isFirst || isLast){
                        newShapeDivider.height = newShapeDivider.height + orderLine.skirt_height;
                    }
                    if(isFirst || isSecondToLast){
                        shapeColumnWidth = (shapeColumnWidth - ringFromEdge) / 2;
                    }
                }

                //hide part of the vertical line for clouded shapes
                if(isFirst){
                    newShapeDivider.dontShow = 'right';
                }
                if(isLast){
                    newShapeDivider.dontShow = 'left';
                }

                //only add divider if shape is not square
                if(!isSquare){
                    $scope.shade.shapeDividers.push(newShapeDivider);
                }

                dividerX = dividerX + shapeColumnWidth;
            }
        };
        buildShadeDrawing.buildPanels = function(){

            var orderLine = $scope.orderLine; //set in view
            var skirtHeight = orderLine.skirt_height;

            //determine panels
            var panels = orderLine.total_panels;
            var panelHeight = orderLine.panel_height;

            var Panel = function(value){
                this.width = $scope.shade.width;
                this.y = 0;
                this.x = $scope.shade.layout.columns.shapeTopLeft.x;
                this.value = value;
            }
            var panelY = panelHeight + $scope.shade.layout.rows.shape.y;
            for(var i = 0; i < panels;i++){

                var newPanel = new Panel(panelHeight);
                newPanel.y = panelY;

                $scope.shade.panels.push(newPanel);

                panelY = panelY + panelHeight;
            }
        };
        buildShadeDrawing.buildMeasurements = function(){

            var shade = $scope.shade;
            var layout = shade.layout;

            //valance measurements
            if(shade.hasValance){
                //valance width
                shade.measurements.push({
                    height: shade.valanceWidth,
                    x: layout.columns.shapeTopLeft.x,
                    y: layout.rows.valanceMeasurement.y,
                    rotate: true,
                    flip: true,
                });
                //valance height
                shade.measurements.push({
                    height: shade.valanceHeight,
                    x: layout.columns.viewboxLeft.x,
                    y: layout.rows.valance.y,
                });
                //valance depth
                shade.measurements.push({
                    height: shade.valanceReturn,
                    x: layout.columns.valanceTopRight.x,
                    y: layout.rows.valanceMeasurement.y,
                    flip: true,
                    rotate: true,
                });
                //valance label
                shade.measurements.push({
                    height: null,
                    x: layout.columns.valanceLabel.x,
                    y: layout.rows.valance.y,
                    textLabel: 'Valance',
                    flip: true,
                });
            }

            //headerboard height
            if(shade.headerboard > 0){
                shade.measurements.push({
                    height: shade.headerboard,
                    x: layout.columns.viewboxLeft.x,
                    y: layout.rows.headerboard.y,
                });
                //headerboard label
                shade.measurements.push({
                    height: null,
                    x: layout.columns.shapeTopRight.x,
                    y: layout.rows.headerboard.y,
                    textLabel: 'Headerboard',
                    flip: true,
                });
            }

            // valance headerboard measurements
            if(shade.valanceHeaderboard > 0){
                //valanceHeaderboard height
                shade.measurements.push({
                    height: shade.valanceHeaderboard,
                    x: layout.columns.viewboxLeft.x,
                    y: layout.rows.valanceHeaderboard.y,
                });
                //valanceHeaderboard label
                shade.measurements.push({
                    height: null,
                    x: layout.columns.shapeTopRight.x,
                    y: layout.rows.valanceHeaderboard.y,
                    textLabel: 'Valance Headerboard',
                    flip: true,
                });
            }

            //shade measurements
            if(shade.hasShade){
                //shade width
                shade.measurements.push({
                    height: shade.width,
                    x: layout.columns.shapeTopLeft.x,
                    y: layout.rows.shadeMeasurement.y,
                    flip: true,
                    rotate: true,
                });
                //column width
                shade.measurements.push({
                    height: Number($scope.orderLine.ring_spacing),
                    x: layout.columns.shapeTopLeft.columns.firstRing.x,
                    y: layout.rows.bottomMeasurement.y,
                    flip: false,
                    rotate: true,
                });
                //shade height
                shade.measurements.push({
                    height: shade.height,
                    x: layout.columns.viewboxLeft.x,
                    y: layout.rows.shape.y,
                });
                //panel height
                shade.measurements.push({
                    height: $scope.orderLine.panel_height,
                    x: layout.columns.panelMeasurement.x,
                    y: layout.rows.shape.rows.panelMeasurement.y,
                    flip: true,
                });
                //skirt height
                shade.measurements.push({
                    height: $scope.orderLine.skirt_height,
                    x: layout.columns.panelMeasurement.x,
                    y: layout.rows.shape.rows.skirt.y,
                    flip: true,
                });
                //ring from edge
                if(!shade.isDogear){
                    shade.measurements.push({
                        height: $scope.orderLine.product.ring_from_edge,
                        x: layout.columns.shapeTopLeft.x,
                        y: layout.rows.bottomMeasurement.y,
                        flip: false,
                        rotate: true,
                    });
                }
            }
            //embellishment height
            if(shade.embellishmentSizing.size_bottom > 0){
                shade.measurements.push({
                    height: shade.embellishmentSizing.size_bottom,
                    x: shade.layout.columns.embellishmentMeasurement.x,
                    y: layout.rows.shape.rows.skirt.embellishment.y,
                    flip: true,
                    textLabel: getPath($scope,'orderLine.embellishment_option.option.name', 0),
                });
            }
            //embellishment inset bottom
            if(shade.embellishmentSizing.inset_size_bottom > 0){
                shade.measurements.push({
                    height: shade.embellishmentSizing.inset_size_bottom,
                    x: layout.columns.embellishmentMeasurement.x,
                    y: layout.rows.shape.rows.skirt.inset.y,
                    flip: true,
                    textLabel: 'Inset',
                });
            }
        };
        buildShadeDrawing.shadeFilters = function(){

            $scope.filterRoundShape = function(value, index, array){

                var validShapes = ['Austrian', 'Balloon', 'Dog Ear', 'PCloud', 'SCloud'];
                if(validShapes.indexOf(value.shape) !== -1){
                    return true;
                }
                return false;
            };
            $scope.filterCloud = function(value, index, array){
                if(value.shape === 'SCloud' || value.shape === 'PCloud'){
                    return true;
                }
                return false;
            };
            $scope.filterBlackVertical = function(value, index, array){

                var validShapes = ['Austrian', 'Balloon', 'Dog Ear'];
                if(validShapes.indexOf(value.shape) !== -1){
                    return true;
                }
                return false;
            };
        };
        buildShadeDrawing.viewApi = function(){};
        function getPath(object, propertyString, defaultValue){

            if(!object){
                return defaultValue;
            }

            var propertyNest = propertyString.split('.');
            for( var i = 0; i<propertyNest.length; i++ ) {
                if(object && object.hasOwnProperty(propertyNest[i])) {
                    object = object[propertyNest[i]];
                } else {
                    object = defaultValue;
                    break;
                }

            }
            return object;
        };
        buildShadeDrawing.setup();
    };

    bestlineShadeDrawing.link = function($scope, element, attrs){};

    return bestlineShadeDrawing;
});
