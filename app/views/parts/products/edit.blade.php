@extends('layout')

@section('title')
    Bestline - Edit product
@stop

@section('javascript-head')
    @parent
    <script src="/js/source/angular/directives/bestline-api-errors.js" type="application/javascript"></script>
    <script src="/js/source/angular/services/bestline-api.js" type="application/javascript"></script>
    <script src="/js/source/angular/parts/forms/bestline-product-form.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-company-prices.js" type="application/javascript"></script>
    <script src="/js/source/angular/parts/forms/bestline-company-price-form.js" type="application/javascript"></script>
    <script src="/js/source/angular/parts/products/edit.js" type="application/javascript"></script>
@stop

@section('main')
	<div ng-controller="PartsProductEditController as vmEdit" ng-cloak class="col-md-12 container">
    <div bestline-api-errors="vmEdit.apiErrors"></div>
    <div class="row">
      <div class="col-xs-12">
        <div ng-if="vmEdit.product" bestline-product-form product="vmEdit.product"></div>
      </div>
    </div>
	</div>
@stop