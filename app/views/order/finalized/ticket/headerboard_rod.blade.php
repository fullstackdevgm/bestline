@section('javascript-head')
    @parent

    <script src="/js/source/angular/controllers/ticket/headerboardRod.js" type="application/javascript"></script>
@stop
<div class="boardTab" ng-controller="HeaderboardRodTicketController as headerboardTicket">
  <div class="row">
    <div class="col-xs-6"><h2>Headerboard & Rod Ticket</h2></div>
    <div class="col-xs-6 text-right"><h2>Invoice: #{[{ finalizedOrder.id }]}</h2></div>
  </div>
  <div class="row">
    <div class="col-xs-6 text-left">
        {[{ finalizedOrder.company.name }]}<br>
        {[{ finalizedOrder.sidemark }]}
    </div>
    <div class="col-xs-6 text-right">
        Due Date: {[{ getDate(finalizedOrder.date_due) }]}
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <h3 class="text-center">{[{ finalizedOrder.product.name }]}</h3>
      <p class="text-lg">
        Cover Fabric: {[{ headerboardTicket.replaceBlackoutThermalWithStock(finalizedOrder.headerboard_cover_fabric.fabric.pattern) }]} {[{ finalizedOrder.headerboard_cover_fabric.fabric.color }]}<br/>
        Total Cut Length: {[{ finalizedOrder.headerboard_cover_cut_length }]}"
      </p>
      <table class="table table">
        <thead>
          <tr>
            <th>Shade #</th>
            <th>Headerboards</th>
            <th>Headerboard Cover</th>
            <th>Rods</th>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="(index, orderLine) in finalizedOrder.order_lines">
            <td>{[{ index + 1 }]}</td>
            <td>
              <p ng-repeat="headerboard in orderLine.headerboard_dimensions track by $index">{[{ headerboard }]}</p>
            </td>
            <td>
              <img class="fabricImage" ng-show="orderLine.headerboard_cover_fabric.fabric.image" ng-src="/uploads/fabrics/{[{ orderLine.headerboard_cover_fabric.fabric.image }]}">
              <img class="fabricImage" ng-show="!orderLine.headerboard_cover_fabric.fabric.image" ng-src="/images/default-fabric-img.png">
              <p>Type: {[{ orderLine.headerboard_cover_fabric.type.name }]}</p>
              <p>Fabric: {[{ headerboardTicket.replaceBlackoutThermalWithStock(orderLine.headerboard_cover_fabric.fabric.pattern) }]} {[{ orderLine.headerboard_cover_fabric.fabric.color }]}</p>
              <p>Cut Length: {[{ orderLine.headerboard_cover_cut_length }]}"</p>
            </td>
            <td>
              <p ng-repeat="dimension in orderLine.rod_dimensions track by $index">{[{ dimension }]}</p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
