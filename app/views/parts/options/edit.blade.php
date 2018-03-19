@extends('layout')

@section('title')
    Bestline - Edit option
@stop

@section('javascript-head')
    @parent
    <script src="/js/source/angular/directives/bestline-api-errors.js" type="application/javascript"></script>
    <script src="/js/source/angular/services/bestline-api.js" type="application/javascript"></script>
    <script src="/js/source/angular/parts/forms/bestline-options-form.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-company-prices.js" type="application/javascript"></script>
    <script src="/js/source/angular/parts/forms/bestline-company-price-form.js" type="application/javascript"></script>
    <script src="/js/source/angular/parts/options/edit.js" type="application/javascript"></script>
@stop

@section('main')
	<div ng-controller="PartsOptionsEditController as vmEdit" ng-cloak class="col-md-12 container">
    <div bestline-api-errors="vmEdit.apiErrors"></div>
    <div class="row">
      <div class="col-xs-12">
        <div ng-if="vmEdit.option" bestline-option-form option="vmEdit.option"></div>
      </div>
    </div>
	</div>
@stop