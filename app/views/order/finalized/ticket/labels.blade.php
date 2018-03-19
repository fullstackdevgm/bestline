@extends('layout-plain')

@section('javascript-head')
    @parent

    <script src="/js/source/angular/services/bestline-api.js" type="application/javascript"></script>
    <script src="/js/source/angular/order/finalized/ticket/label.js" type="application/javascript"></script>
@stop

@section('content')
<div class="templates templateTicketLabels container"
    ng-controller="TicketLabelController as vmLabel"
    ng-cloak>
    <h1>Label Print</h1>
    <div ng-repeat="orderLine in vmLabel.order.order_lines"
        class="printLabel text-center">
        <h2 class="ellipsis-overflow">{[{ vmLabel.order.company.name }]}</h2>
        <p class="ellipsis-overflow">{[{ vmLabel.order.company.shipping_address.city }]} / {[{ vmLabel.order.company.shipping_address.state }]}</p>
        <h3 class="ellipsis-overflow">{[{ vmLabel.order.sidemark }]}</h3>
        <p class="ellipsis-overflow">{[{ vmLabel.order.product.name }]}</p>
        <p class="ellipsis-overflow">#{[{ $index }]} - {[{ orderLine.width }]} X {[{ orderLine.height }]} Cord: {[{ orderLine.cord_position.code }]}</p>
    </div>
</div>
@stop