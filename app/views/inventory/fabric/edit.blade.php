@extends('layout')

@section('title')
    Bestline - Fabric edit
@stop

@section('stylesheets')
    @parent
    <link rel="stylesheet" href="/css/vendor/jquery.dataTables.min.css" type="text/css"/>
@stop

@section('javascript-head')
    @parent
    <script src="/js/vendor/bower_components/webcamjs/webcam.min.js" type="application/javascript"></script>
    <script src="/js/vendor/bower_components/ng-camera/dist/ng-camera.js" type="application/javascript"></script>
    <script src="/js/source/angular/includes/inject-ng-camera.js" type="application/javascript"></script>
    <script src="/js/vendor/bower_components/angular-file-upload/dist/angular-file-upload.min.js" type="application/javascript"></script>
    <script src="/js/source/angular/includes/inject-angular-file-upload.js" type="application/javascript"></script>
    <script src="/js/source/angular/services/bestline-api.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-api-errors.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-company-prices.js" type="application/javascript"></script>
    <script src="/js/source/angular/parts/forms/bestline-company-price-form.js" type="application/javascript"></script>
    <script src="/js/source/angular/controllers/inventory/fabric/edit.js" type="application/javascript"></script>
@stop

@section('javascript')
    @parent
    <script src="/js/vendor/bootstrap-datepicker.js" type="application/javascript"></script>
@stop

@section('main')
    <div class="pull-right">
        <a ng-show="vmEdit.isCom" href="/inventory/fabric/edit?com=1" class="btn btn-default">
            <i class="fa fa-fw fa-plus-square"></i> New Com Fabric
        </a>
        <a ng-hide="vmEdit.isCom" href="/inventory/fabric/edit" class="btn btn-default">
            <i class="fa fa-fw fa-plus-square"></i> Add New Fabric
        </a>
        <a href="/inventory" class="btn btn-default">Back to Inventory</a>
    </div>

    <div class="templateFabricEdit templates col-md-12" ng-controller="InventoryFabricController as vmEdit" ng-cloak>
        <h1>
            <span ng-show="vmEdit.isCom">COM</span>
            Fabric: {[{ vmEdit.fabric.name }]}
            <span ng-show="vmEdit.loadingFabric"><i class="fa fa-circle-o-notch fa-spin"></i></span>
        </h1>
        <hr/>
        <div ng-hide="vmEdit.loadingFabric">
            <div class="row">

                <div class="col-md-3 col-print-3">
                    <div class="imageBox b-margin b-bottom" ng-hide="vmEdit.showVideoCapture">
                        <div class="imageOverlay" ng-show="vmEdit.uploader.isUploading">
                            <i class="fa fa-circle-o-notch fa-spin"></i>
                        </div>
                        <img ng-show="vmEdit.fabric.image"
                            ng-src="/uploads/fabrics/{[{ vmEdit.fabric.image }]}"
                            alt="{[{ vmEdit.fabric.name }]}"
                        />
                        <img ng-hide="vmEdit.fabric.image"
                            ng-src="/images/default-fabric-img.png"
                            alt="{[{ vmEdit.fabric.name }]}"
                        />
                        <div bestline-api-errors="vmEdit.uploadErrors" class="b-margin b-top"></div>
                    </div>

                    <div class="webcamBox" ng-if="vmEdit.showVideoCapture">
                        <div ng-hide="vmEdit.picture">
                            <ng-camera
                                output-height="232"
                                output-width="310"
                                viewer-height="232"
                                viewer-width="310"
                                image-format="jpeg"
                                jpeg-quality="100"
                                action-message="Take picture"
                                snapshot="vmEdit.picture"
                                flash-fallback-url="/js/vendor/bower_components/webcamjs/webcam.swf">
                            </ng-camera>
                        </div>
                        <div ng-if="vmEdit.picture">
                            <img ng-src="{[{ vmEdit.picture }]}" class="b-margin b-bottom">

                            <div class="row b-margin b-bottom">
                                <div class="col-md-6">
                                    <a ng-hide="vmEdit.uploadingPicture" class="btn btn-block btn-primary" href="" ng-click="vmEdit.saveWebcamPicture()">
                                        Save Image
                                    </a>
                                    <a ng-show="vmEdit.uploadingPicture" class="btn btn-block btn-default" href="" ng-click="vmEdit.saveWebcamPicture()">
                                        <i class="fa fa-circle-o-notch fa-spin"></i>
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="" class="btn btn-block btn-default" ng-click="vmEdit.picture = null">
                                        Take Again
                                    </a>
                                </div>
                            </div>

                            <div bestline-api-errors="vmEdit.uploadErrors"></div>
                        </div>
                    </div>

                    <div class="hide-when-printing">
                        <div class="row b-margin b-bottom">
                            <div class="col-md-6">
                                <span class="file-input btn btn-block btn-success btn-file">
                                    <i class="fa fa-fw fa-file-image-o"></i><span class="small"> Upload<span> <input type="file" nv-file-select uploader="vmEdit.uploader" options="vmEdit.uploaderOptions" class="btn btn-default">
                                </span>
                            </div>
                            <div class="col-md-6">
                                <a href="" class="btn btn-block btn-success" ng-click="vmEdit.showVideoCapture = (vmEdit.showVideoCapture)? false : true;">
                                    <i class="fa fa-fw fa-file-image-o"></i><span class="small"> Capture</span>
                                </a>
                            </div>
                        </div>

                        <p>

                    </div>
                </div>

                <div class="col-xs-9 col-print-9">

                    <div ng-if="vmEdit.isCom">
                        <div class="row">
                            <div class="form-group col-print-6 col-xs-6">
                                <label for="sidemark">Sidemark</label>
                                <input ng-model="vmEdit.fabric.sidemark" type="text" name="sidemark" class="form-control">
                            </div>
                            <div class="form-group col-print-6 col-xs-6">
                                <label for="owner_company_id">Company</label>
                                <select class="form-control"
                                    ng-model="vmEdit.fabric.owner_company_id"
                                    ng-options="+(companyId) as companyName for (companyId, companyName) in vmEdit.fabric.select_options.companies">
                                    <option value="">Select a company...</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-print-6 col-xs-6">
                            <label for="pattern">Pattern</label>
                            <input ng-model="vmEdit.fabric.pattern" type="text" name="pattern" class="form-control">
                        </div>
                        <div class="form-group col-print-6 col-xs-6">
                            <label for="color">Color</label>
                            <input ng-model="vmEdit.fabric.color" type="text" name="color" class="form-control">
                        </div>
                    </div>

                    <div class="row" ng-if="vmEdit.isCom">
                        <div class="form-group col-print-6 col-xs-6">
                            <label for="date_received">Date Received</label>
                            <input ng-model="vmEdit.fabric.date_received" type="text" name="date_received" class="form-control" data-provide="datepicker" data-date-format='yyyy-mm-dd'/>
                        </div>
                        <div class="form-group col-print-6 col-xs-6">
                            <label for="ticket_number">Ticket Number</label>
                            <input ng-model="vmEdit.fabric.ticket_number" type="text" name="ticket_number" class="form-control"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-print-6 col-xs-6">
                            <div class="form-group">
                                <label for="type_ids">Fabric Type</label>
                                <select name="type_ids" class="form-control select-multiple" multiple="multiple" ng-size="{[{ vmEdit.fabric.select_options.fabric_types.length }]}"
                                    ng-model="vmEdit.fabric.type_ids"
                                    ng-options="+(typeId) as typeName for (typeId, typeName) in vmEdit.fabric.select_options.fabric_types">
                                </select>
                            </div>
                        </div>
                        <div class="col-print-6 col-xs-6">
                            <div class="form-group">
                                <label for="related_option_id">Related Option</label>
                                <select class="form-control input-sm"
                                    ng-model="vmEdit.fabric.related_option_id"
                                    ng-options="+(optionId) as optionName for (optionId, optionName) in vmEdit.fabric.select_options.options">
                                    <option value="">Select a Related Option...</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label for="width">Width</label>
                                <input ng-model="vmEdit.fabric.width" type="text" name="width" class="form-control">
                                <span class="unit">in.</span>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label for="repeat">Repeat</label>
                                <input ng-model="vmEdit.fabric.repeat" type="text" name="repeat" class="form-control">
                                <span class="unit">in.</span>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label for="grade">Grade</label>
                                <select class="form-control"
                                    ng-model="vmEdit.fabric.grade"
                                    ng-options="gradeSlug as gradeName for (gradeSlug, gradeName) in vmEdit.fabric.select_options.grades">
                                    <option value="">Select a grade...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label for="minimum_qty">Min Quantity Warning</label>
                                <input ng-model="vmEdit.fabric.minimum_qty" type="text" name="minimum_qty" rows="5" class="form-control">
                                <span class="unit">in.</span>
                            </div>
                        </div>
                    </div>

                    <div ng-show="!vmEdit.isCom" class="well">
                        <div class="row">
                        <div class="col-md-3 col-print-6">
                            <h4>Pricing</h4>
                            <div class="form-group">
                                <label for="unit_price">Unit Price</label>
                                <input ng-model="vmEdit.fabric.unit_price" type="text" name="unit_price" class="form-control">
                            </div>
                                <div class="form-group">
                                <label for="pricing_type">Pricing Type</label>
                                <select class="form-control"
                                    ng-model="vmEdit.fabric.pricing_type"
                                    ng-options="typeSlug as typeName for (typeSlug, typeName) in vmEdit.fabric.select_options.pricing_types">
                                    <option value="">Select a pricing type...</option>
                                </select>

                            </div>
                        </div>
                        <div class="col-md-9 col-print-9">
                            <h4>Company Pricing</h4>
                            <div bestline-company-prices bc-prices="vmEdit.companyPrices" bc-parent="vmEdit.fabric" bc-options="vmEdit.companyPricesOptions" ng-hide="vmEdit.isCom" class=""></div>
                        </div>
                    </div>
                    </div>

                    <div class="row" ng-if="vmEdit.isCom">
                        <div class="col-md-8">
                            <label for="flaws_string">Flaws (comma separated list)</label>
                            <div class="form-group">
                                <input type="text" name="flaws_string" ng-model="vmEdit.fabric.flaws_string" class="form-control">
                                <span class="unit">in.</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <input ng-model="vmEdit.fabric.notes" type="text" name="notes" rows="5" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-print-3" ng-hide="vmEdit.fabric.id">
                            <div class="form-group">
                                <label for="quantity">COM Starting Quantity</label>
                                <input ng-model="vmEdit.fabric.quantity" type="text" name="quantity" rows="5" class="form-control">
                                <span class="unit">inches</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-xs-2 col-md-offset-10 pull-right">
                    <button ng-hide="vmEdit.savingFabric" class="btn btn-primary btn-block" ng-click="vmEdit.saveFabric(vmEdit.fabric)">
                        <i class="fa fa-fw fa-save"></i> Save Record
                    </button>
                    <button ng-show="vmEdit.savingFabric" class="btn btn-default btn-block">
                        <i class="fa fa-circle-o-notch fa-spin"></i>
                    </button>
                    <div bestline-api-errors="vmEdit.saveErrors"></div>
                </div>
            </div>

            <h3>Inventory Management</h3>
            <div ng-if="vmEdit.fabric.id">
                <div class="hide-when-printing well">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Quantity on Hand:</label>
                            <input value="0" type="text" ng-model="vmEdit.fabric.inventory.quantity" disabled class="form-control">
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="adjustment">Adjustment</label>
                                <input ng-model="vmEdit.adjustment.adjustment" type="text" name="adjustment" class="form-control">
                                <span class="unit">inches</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group input-group reason-field">
                                <label for="reason">Reason</label>
                                <input ng-model="vmEdit.adjustment.reason" type="text" name="reason" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                        <br>
                            <span class="input-group-btn">
                                <button ng-hide="vmEdit.adjustingFabric" class="btn btn-default" type="button" ng-click="vmEdit.adjustFabric(vmEdit.adjustment)"><i class="fa fa-save"></i> Add Adjustment</button>
                                <button ng-show="vmEdit.adjustingFabric" class="btn btn-default" type="button"><i class="fa fa-circle-o-notch fa-spin"></i></button>
                            </span>
                        </div>
                        <div bestline-api-errors="vmEdit.adjustmentApiErrors" class="b-margin b-top"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr/>
                        <h4>Adjustment History</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped" id="inventoryTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>User</th>
                                    <th>Adjustment</th>
                                    <th>Reason</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="inventory in vmEdit.fabric.all_inventory">
                                    <td>{[{ inventory.created_at }]}</td>
                                    <td>{[{ inventory.by_user_id }]}</td>
                                    <td>{[{ inventory.adjustment }]}</td>
                                    <td>{[{ inventory.reason }]}</td>
                                    <td>{[{ inventory.quantity }]}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
