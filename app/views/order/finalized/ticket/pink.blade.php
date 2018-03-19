@section('javascript-head')
    @parent

    <script src="/js/source/angular/controllers/ticket/pink.js" type="application/javascript"></script>
@stop
<div class="pinkTab" ng-controller="PinkTicketController as pinkTicket">
  <h2>Pink Ticket</h2>
  <div class="row">
    <div class="col-xs-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <b>Invoice:</b> #{[{ finalizedOrder.id }]}
          <span class="pull-right"><b>Date Received:</b> {[{ getDate(finalizedOrder.date_received) }]} <b>Date Due:</b> {[{ getDate(finalizedOrder.date_due) }]}</span>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-3">
      <div class="panel panel-default">
        <div class="panel-heading">
          <b>Order Info</b>
        </div>
        <table class="table table-bordered">
          <tr>
            <th>Company:</th>
            <td>{[{ finalizedOrder.company.name }]}</td>
          </tr>
          <tr>
            <th>Contact:</th>
            <td>{[{ finalizedOrder.contact.first_name }]} {[{ finalizedOrder.contact.last_name }]}</td>
          </tr>
          <tr>
            <th>Sidemark:</th>
            <td>{[{ finalizedOrder.sidemark }]}</td>
          </tr>
          <tr>
            <th>PO:</th>
            <td>{[{ finalizedOrder.purchase_order }]}</td>
          </tr>
          <tr>
            <th>Shipping Method:</th>
            <td><span>{[{ finalizedOrder.shipping_method.name }]}</span><span ng-if="finalizedOrder.shipping_method.name === 'Delivery' && finlizedOrder.shipping_address.area !== ''">: {[{ finalizedOrder.shipping_address.area }]}</span></td>
          </tr>
        </table>
      </div>
    </div>
    <div class="col-xs-9">
      <div class="panel panel-default">
        <div class="panel-heading">
          <b>Materials</b>
        </div>
        <div class="panel-body">
          <table class="table table-hover" style="width: 100%;">
            <thead>
              <tr>
                <th>Fabric Type</th>
                <th>Image</th>
                <th>Name</th>
                <th>Width</th>
                <th>Repeat</th>
                <th>Total Inches</th>
              </tr>
            </thead>
            <tbody>
              <tr ng-repeat="fabric in finalizedOrder.fabrics">
                <td>{[{ fabric.type.name }]}</td>
                <td>
                  <img class="fabricImage" ng-show="fabric.fabric.image" ng-src="/uploads/fabrics/{[{ fabric.fabric.image }]}">
                  <img class="fabricImage" ng-show="!fabric.fabric.image" ng-src="/images/default-fabric-img.png">
                </td>
                <td>{[{ fabric.fabric.name }]}</td>
                <td>{[{ fabric.fabric.width | bestlineInches }]}</td>
                <td>{[{ fabric.fabric.repeat | bestlineInches }]}</td>
                <td>{[{ fabric.cut_length * fabric.cuts }]}</td>
              </tr>
              <tr ng-show="pinkTicket.isUnlined(finalizedOrder)">
                <td>Lining</td>
                <td>&nbsp;</td>
                <td colspan="5"><span class="text-danger"><b>UNLINED</b></span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <div class="panel panel-default">
        <div class="panel-heading orderItemsHeading">
          <b>Order Items</b>
        </div>
        <div class="panel-body">
          <table class="table table-hover">
            <thead>
              <tr>
                <th></th>
                <th>Type</th>
                <th>Mount</th>
                <th>Width</th>
                <th>Return</th>
                <th>Height</th>
                <th>H board</th>
                <th>Hardware</th>
                <th>Pos</th>
                <th>Len</th>
                <th>Pull Type</th>
              </tr>
            </thead>
            <tbody>
              <tr ng-repeat="(index, orderLine) in finalizedOrder.order_lines">
                <td>#{[{ index + 1 }]}</td>
                <td>
                  <p ng-if="orderLine.has_shade">Shade - {[{ orderLine.product.name }]}</p>
                  <p ng-if="orderLine.has_valance">Valance - {[{ orderLine.valance_type.name }]}</p>
                  <p ng-show="orderLine.options.length > 0">
                    Options: <span ng-repeat="option in orderLine.options"><span ng-if="!$first">, </span><span>{[{ option.sub_option.name }]}</span></span>
                  </p>
                </td>
                <td>{[{ orderLine.mount.description }]}</td>
                <td>
                  <p ng-if="orderLine.has_shade">{[{ orderLine.width | bestlineInches }]}</p>
                  <p ng-if="orderLine.has_valance">{[{ orderLine.valance_width | bestlineInches }]}</p>
                </td>
                <td>
                  <p ng-if="orderLine.has_shade">{[{ orderLine.return | bestlineInches }]}</p>
                  <p ng-if="orderLine.has_valance">{[{ orderLine.valance_return | bestlineInches }]}</p>
                </td>
                <td>
                  <p ng-if="orderLine.has_shade">{[{ orderLine.height | bestlineInches }]}</p>
                  <p ng-if="orderLine.has_valance">{[{ orderLine.valance_height | bestlineInches }]}</p>
                </td>
                <td>
                  <p ng-if="orderLine.has_shade">{[{ orderLine.headerboard | bestlineInches }]}</p>
                  <p ng-if="orderLine.has_valance">{[{ orderLine.valance_headerboard | bestlineInches }]}</p>
                </td>
                <td>{[{ orderLine.hardware.description }]}</td>
                <td>{[{ orderLine.cord_position.description }]}</td>
                <td>{[{ orderLine.cord_length }]}</td>
                <td>{[{ orderLine.pull_type.description }]}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="row" ng-if="finalizedOrder.ticket_notes">
    <div class="col-xs-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <b>Manufacturing Notes</b>
        </div>
        <div class="panel-body">
          <span>
            {[{ finalizedOrder.ticket_notes }]}
          </span>
        </div>
      </div>
    </div>
  </div>
</div>
