@section('javascript-head')
    @parent

    <script src="/js/vendor/bower_components/svg-pan-zoom/dist/svg-pan-zoom.min.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-svg-pan-zoom.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-viewbox.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-svg-measurement.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-calculate-trim-points.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-shade-drawing.js" type="application/javascript"></script>
    <script src="/js/source/angular/controllers/ticket/item.js" type="application/javascript"></script>
@stop
<div class="itemTab"
    ng-controller="TicketItemController"
    ng-show="activeTab === 'item'+index">
    <div class="row">
        <div class="col-xs-4">
            <table class="table table-no-border table-condensed">
                <tbody>
                    <tr>
                        <td><strong>Account</strong></td>
                        <td>{[{ finalizedOrder.company.name }]}</td>
                    </tr>
                    <tr>
                        <td><strong>Side Mark</strong></td>
                        <td>{[{ finalizedOrder.sidemark }]}</td>
                    </tr>
                    <tr>
                        <td><strong>Date In</strong></td>
                        <td>{[{ getDate(finalizedOrder.date_received) }]}</td>
                    </tr>
                    <tr>
                        <td><strong>Date Out</strong></td>
                        <td>{[{ getDate(finalizedOrder.date_due) }]}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-xs-4 text-center">
            <h2>{[{ orderLine.product.name }]}</h2>
        </div>
        <div class="col-xs-4 text-right">
            <h2>Invoice: #{[{ finalizedOrder.id }]}</h2>
            <h4>Ticket: #{[{ $index+1 }]}</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-8">
            <table class="table table-striped table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Color</th>
                        <th>Width</th>
                        <th>Repeat</th>
                        <th>Cuts</th>
                        <th>Cut Length</th>
                    </tr>
                </thead>
                <tbody>
                <tr ng-repeat="fabric in orderLine.fabrics">
                    <td>{[{ fabric.type.name }]}</td>
                    <td>{[{ fabric.fabric.pattern }]}</td>
                    <td>{[{ fabric.fabric.color }]}</td>
                    <td>{[{ fabric.fabric.width }]}</td>
                    <td>{[{ fabric.fabric.repeat }]}</td>
                    <td>{[{ fabric.cuts }]}</td>
                    <td>{[{ fabric.cut_length }]}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-xs-4">
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th><strong>Options</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="option in orderLine.options" ng-show="orderLine.options.length > 0">
                        <td>
                            <span ng-if="option.sub_option.name !== 'Default'">{[{ option.sub_option.name }]}</span><span ng-if="option.sub_option.name === 'Default'">{[{ option.option.name }]}</span><span ng-show="option.data.size_bottom">, Size Bottom: {[{ option.data.size_bottom }]}"</span><span ng-show="option.data.size_sides">, Size Sides: {[{ option.data.size_sides }]}"</span><span ng-show="option.data.size_top">, Size Top: {[{ option.data.size_top }]}"</span><span ng-show="option.data.inset_size_sides">, Inset Sides: {[{ option.data.inset_size_sides }]}"</span><span ng-show="option.data.inset_size_bottom">, Inset Bottom: {[{ option.data.inset_size_bottom }]}"</span><span ng-show="option.data.inset_size_top">, Inset Top: {[{ option.data.inset_size_top }]}"</span>
                        </td>
                    </tr>
                    <tr ng-show="orderLine.options.length === 0"><td>This item has no options.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">

        <div class="col-xs-2 text-right">
            <h2 class="finishedValue">Finished Width: </h2>
        </div>
        <div class="col-xs-2">
            <h2> {[{ orderLine.manufacturing_width }]}</h2>
        </div>
        <div class="col-xs-2 text-right">
            <h2 class="finishedValue">Finished Height:</h2>
        </div>
        <div class="col-xs-2">
            <h2> {[{ orderLine.manufacturing_length}]}</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                  <b>Seamstress Notes</b>
                </div>
                <table class="table table-condensed table-bordered">
                    <tbody>
                        <tr ng-show="orderLine.manufacturing_valance_headerboard && orderLine.manufacturing_valance_headerboard > 0">
                            <td><strong>Valance Board Size:</strong></td>
                            <td>{[{ orderLine.manufacturing_valance_headerboard }]}</td>
                        </tr>
                        <tr ng-show="orderLine.manufacturing_headerboard && orderLine.manufacturing_headerboard > 0">
                            <td><strong>Board Size:</strong></td>
                            <td>{[{ orderLine.manufacturing_headerboard }]}</td>
                        </tr>
                        <tr>
                            <td><strong>Panel Height:</strong></td>
                            <td>{[{ orderLine.panel_height }]}</td>
                        </tr>
                        <tr>
                            <td><strong>Number of Panels:</strong></td>
                            <td>{[{ orderLine.total_panels }]}</td>
                        </tr>
                        <tr ng-show="orderLine.embellishment_option.data.size_bottom">
                            <td><strong>Embellishment Height:</strong></td>
                            <td>{[{ orderLine.embellishment_option.data.size_bottom }]}</td>
                        </tr>
                        <tr ng-show="orderLine.embellishment_option.data.inset_size_bottom">
                            <td><strong>Embellishment Inset Bottom:</strong></td>
                            <td>{[{ orderLine.embellishment_option.data.inset_size_bottom }]}</td>
                        </tr>
                        <tr>
                            <td><strong>Skirt Height:</strong></td>
                            <td>{[{ orderLine.skirt_height }]}</td>
                        </tr>
                        <tr ng-show="orderLine.seamstress_notes.length > 0">
                            <td colspan="4">
                                <span ng-repeat="note in orderLine.seamstress_notes">{[{ note }]}<br/></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                  <b>Cleaner Notes</b>
                </div>
                <table class="table table-condensed table-bordered">
                    <tbody>
                        <tr>
                            <td><strong>Ring Type</strong></td>
                            <td>{[{ finalizedOrder.product.ring_type.description }]}</td>
                        </tr>
                        <tr>
                            <td><strong>Ring From Edge</strong></td>
                            <td>{[{ orderLine.product.ring_from_edge }]}</td>
                        </tr>
                        <tr>
                            <td><strong>Ring Spacing</strong></td>
                            <td>{[{ orderLine.ring_spacing }]}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Columns</strong></td>
                            <td>{[{ orderLine.total_ring_columns }]}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                  <b>Assembler Notes</b>
                </div>
                <table class="table table-condensed table-bordered">
                    <tbody>
                        <tr>
                            <td><b>Stringing</b></td>
                            <td colspan="3">{[{ orderLine.hardware.description }]}</td>
                        </tr>
                        <tr>
                            <td><b>Cord Position</b></td>
                            <td>{[{ orderLine.cord_position.description }]}</td>
                            <td><b>Length</b></td>
                            <td>{[{ orderLine.cord_length }]}</td>
                        </tr>
                        <tr>
                            <td><b>Pull Type</b></td>
                            <td colspan="3">{[{ orderLine.pull_type.description }]}</td>
                        </tr>
                        <tr>
                            <td><b>Mount</b></td>
                            <td colspan="3">{[{ orderLine.mount.description }]}</td>
                        </tr>
                        <tr ng-show="orderLine.assembler_notes.length > 0">
                            <td colspan="4">
                                <span ng-repeat="note in orderLine.assembler_notes">{[{ note }]}<br/></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-xs-9">
            <div class="panel panel-default">
                <div class="panel-body">

                    <bestline-shade-drawing order-line="orderLine" pan-zoom="panZoom"></bestline-shade-drawing>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <b>Manufacturing Notes</b>
                </div>
                <div class="panel-body">
                    <p>{[{ finalizedOrder.ticket_notes }]}</p>
                </div>
            </div>
        </div>
    </div>
</div>
