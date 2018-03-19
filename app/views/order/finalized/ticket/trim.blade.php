@section('javascript-head')
  @parent

   <script src="/js/source/angular/controllers/ticket/trim.js" type="application/javascript"></script>
@stop

<div class="trimTab" ng-controller="TrimTicketController as trimTicket">
  <div class="row">
    <div class="col-xs-6"><h2>Trim Ticket</h2></div>
    <div class="col-xs-6 text-right"><h2>Invoice: #{[{ finalizedOrder.id }]}</h2></div>
  </div>
  <div class="row" style="padding-bottom: 20px;">
    <div class="col-xs-6">
        {[{ finalizedOrder.company.name }]}<br/>
        {[{ finalizedOrder.sidemark }]}
    </div>
    <div class="col-xs-6 text-right">
        Due Date: {[{ getDate(finalizedOrder.date_due) }]}
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <table class="table table">
        <thead>
          <tr>
            <th>Product Name</th>
            <th>Trim Fabric</th>
            <th>Width</th>
            <th>Return</th>
            <th>Length</th>
            <th>Mount</th>
            <th>Headerboard</th>
            <th>Notes</th>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="(index, orderLine) in finalizedOrder.order_lines">
            <td>
              <p>{[{ orderLine.product.name }]}</p>
              <p ng-show="orderLine.embellishment_option" style="padding-left: 30px;">
                <span>{[{ orderLine.embellishment_option.sub_option.name }]}</span><br/>
                
                <ul style="margin-left: 15px; margin-top: -7px">
                  <li ng-repeat="optionData in trimTicket.optionData" ng-if="orderLine.embellishment_option.data[optionData.key]"><span ng-show="orderLine.embellishment_option.data[optionData.key]">{[{ optionData.name }]}: {[{ orderLine.embellishment_option.data[optionData.key] }]}"</span></li>
                </ul>
              </p>
              <p ng-show="orderLine.embellishment_option.cuttings" ng-repeat="cutting in orderLine.embellishment_option.cuttings" style="padding-left: 30px;">
                Cutting: {[{ cutting.count }]} @ {[{ cutting.length }]}" with {[{ cutting.width }]}" cut and {[{ cutting.finished }]}" finished
              </p>
            </td>
            <td>
              <img class="fabricImage" ng-show="orderLine.embellishment_option.order_fabric.fabric.image" ng-src="/uploads/fabrics/{[{ orderLine.embellishment_option.order_fabric.fabric.image }]}">
              <img class="fabricImage" ng-show="!orderLine.embellishment_option.order_fabric.fabric.image" ng-src="/images/default-fabric-img.png">
              <p>{[{ orderLine.embellishment_option.order_fabric.fabric.name }]}</p>
            </td>
            <td>{[{ orderLine.width }]}</td>
            <td>{[{ orderLine.return }]}</td>
            <td>{[{ orderLine.height }]}</td>
            <td>{[{ orderLine.mount.description }]}</td>
            <td>{[{ orderLine.headerboard }]}</td>
            <td>
              <span ng-show="orderLine.embellisher_notes.length > 0" style="padding-left: 15px;">
                <span ng-repeat="note in orderLine.embellisher_notes"><span ng-if="!$first">, </span><span>{[{ note }]}</span></span>
              </span>
            </td>
          </tr>
        </tbody>
      </table>
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