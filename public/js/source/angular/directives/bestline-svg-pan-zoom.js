/*
This code is based on code found at https://github.com/Promact/angular-svg-pan-zoom.git

It had to be modified because the origin was out of date with updates to svg-pan-zoom
 */

var BestLineSVGPanZoom;
(function (BestLineSVGPanZoom) {
    var SvgPanZoomDirective = (function () {
        function SvgPanZoomDirective(spz, $rootScope) {
            var _this = this;
            this.spz = spz;
            this.restrict = "A";
            this.scope = {
                panZoom: '='
            };
            this.link = function ($scope, $element, $attrs) {
                var bZoom = function (scale) {
                    _this.$rootScope.$broadcast("beforeZoom", scale);
                };
                var oZoom = function (scale) {
                    _this.$rootScope.$broadcast("onZoom", scale);
                };
                var bPan = function (point) {
                    _this.$rootScope.$broadcast("beforePan", point);
                };
                var oPan = function (point) {
                    _this.$rootScope.$broadcast('onPan', point);
                };
                var panEnabled = _this.CheckForBoolean($attrs.panEnabled, true), 
                    controlIconEnabled = _this.CheckForBoolean($attrs.controlIconsEnabled, false), 
                    zoomEnabled = _this.CheckForBoolean($attrs.zoomEnabled, true), 
                    dblClickZoomEnabled = _this.CheckForBoolean($attrs.dblClickZoomEnabled, true), 
                    zoomScaleSensitivity = $attrs.zoomScaleSensitivity || 0.2, 
                    minZoom = $attrs.minZoom || 0.5, 
                    maxZoom = $attrs.maxZoom || 10, 
                    fit = _this.CheckForBoolean($attrs.fit, true), 
                    center = _this.CheckForBoolean($attrs.center, true), 
                    refreshRate = $attrs.refreshRate || 'auto', 
                    beforeZoom = $attrs.beforeZoom || bZoom, 
                    onZoom = $attrs.onZoom || oZoom, 
                    beforePan = $attrs.beforePan || bPan, 
                    onPan = $attrs.onPan || oPan, 
                    contain = _this.CheckForBoolean($attrs.contain, true), 
                    viewportSelector = $attrs.viewportSelector || null;

                $scope.panZoom = _this.spz($element[0], {
                    panEnabled: panEnabled,
                    controlIconsEnabled: controlIconEnabled,
                    zoomEnabled: zoomEnabled,
                    dblClickZoomEnabled: dblClickZoomEnabled,
                    zoomScaleSensitivity: zoomScaleSensitivity,
                    minZoom: minZoom,
                    maxZoom: maxZoom,
                    fit: fit,
                    contain: contain,
                    center: center,
                    refreshRate: refreshRate,
                    beforeZoom: beforeZoom,
                    onZoom: onZoom,
                    beforePan: beforePan,
                    onPan: onPan,
                    viewportSelector: viewportSelector,
                });
            };
            this.$rootScope = $rootScope;
        }
        SvgPanZoomDirective.prototype.CheckForBoolean = function (value, defaultValue) {
            if (value === undefined)
                return defaultValue;
            return value === "false" ? false : true;
        };
        return SvgPanZoomDirective;
    })();
    BestLineSVGPanZoom.SvgPanZoomDirective = SvgPanZoomDirective;
})(BestLineSVGPanZoom || (BestLineSVGPanZoom = {}));

angular.module("BestlineSvgPanZoom", []).constant("spz", svgPanZoom).directive("bestlineSvgPanZoom", [
    "spz", "$rootScope", function (spz, $rootScope) {
        return new BestLineSVGPanZoom.SvgPanZoomDirective(spz, $rootScope);
    }]);

/*
The MIT License (MIT)

Copyright (c) 2014 Promact

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.


 */
