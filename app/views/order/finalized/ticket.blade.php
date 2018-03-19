@extends('layout')

@section('title')
    Bestline - Ticket
@stop

@section('javascript-head')
    @parent

    <script src="/js/source/angular/services/bestline-api.js" type="application/javascript"></script>
    <script src="/js/source/angular/controllers/ticket.js" type="application/javascript"></script>
@stop

@section('main')
<div class="templates templateTicket container"
    ng-controller="TicketController"
    ng-class="{
        temporaryPrintStyles: showTemporaryPrintStyles,
        showPinkPrintStyles: showPinkPrintStyles
    }"
    ng-cloak>
    <div class="row ticketHeaderRow">
        <div class="col-md-6">
            <h1>Ticket</h1>

        </div>
        <div class="col-md-6">
            <a class="printAll btn btn-primary btn-small pull-right" title="Print Tickets" href="" ng-click="print()">Print All</a>
            <a class="printPink btn btn-primary btn-small pull-right" title="Print Pink Ticket" href="" ng-click="printPink()">Print Pink</a>
            <a class="printBtn btn btn-primary btn-small pull-right" href="/order/final/ticket/labels?order={[{ searchParams.order }]}" target="_blank">Print Labels</a>
        </div>
    </div>
    <ul class="nav nav-tabs tabs-up" id="ticketTabs">
        <li ng-class="{active: activeTab === 'pink'}"><a href="#pink" ng-click="activeTab = 'pink'">Pink</a></li>
        <li ng-class="{active: activeTab === 'headerboard-rod'}"><a href="#headerboard-rod" ng-click="activeTab = 'headerboard-rod'">Headerboard &amp; Rod</a></li>
        <li ng-class="{active: activeTab === 'trim'}" ng-show="hasEmbellishmentFabric(finalizedOrder)"><a href="#trim" ng-click="activeTab = 'trim'">Trim</a></li>
        <li ng-class="{active: (activeTab === 'side-tabs')}" ng-show="finalizedOrder && finalizedOrder.sidetabs"><a href="#side-tabs" ng-click="activeTab = 'side-tabs'">Side Tabs</a></li>
        <li ng-class="{active: activeTab === 'item{[{ index }]}'}"
            ng-repeat="(index, orderLine) in finalizedOrder.order_lines">
            <a href="#item{[{ index }]}"
                ng-click="setActiveTab('item'+index)"  >
               Item {[{ index + 1 }]}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="pink" ng-show="activeTab === 'pink'">@include('order.finalized.ticket.pink')</div>
        <div class="headerboard-rod" ng-show="activeTab === 'headerboard-rod'">@include('order.finalized.ticket.headerboard_rod')</div>
        <div class="trim" ng-show="activeTab === 'trim'" ng-if="hasEmbellishmentFabric(finalizedOrder)">@include('order.finalized.ticket.trim')</div>
        <div class="side-tabs" ng-show="activeTab === 'side-tabs'" ng-if="finalizedOrder && finalizedOrder.sidetabs">@include('order.finalized.ticket.side_tabs')</div>
        <div class="item-ticket ticket-num-{[{ index }]}"
            ng-repeat="(index, orderLine) in finalizedOrder.order_lines">
            @include('order.finalized.ticket.item')
        </div>
    </div>
</div>
@stop
