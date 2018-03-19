@extends('layout')

@section('title')
    Bestline - Products
@stop

@section('javascript-head')
    @parent
    <script src="/js/vendor/bower_components/angular-smart-table/dist/smart-table.min.js" type="application/javascript"></script>
    <script src="/js/source/angular/includes/inject-angular-smart-table.js" type="text/javascript"></script>
    <script src="/js/source/angular/directives/bestline-api-errors.js" type="application/javascript"></script>
    <script src="/js/source/angular/services/bestline-api.js" type="application/javascript"></script>
    <script src="/js/source/angular/parts/forms/bestline-product-form.js" type="application/javascript"></script>
    <script src="/js/source/angular/parts/forms/bestline-company-price-form.js" type="application/javascript"></script>
    <script src="/js/source/angular/parts/products.js" type="application/javascript"></script>
@stop

@section('main')
  <div ng-controller="PartsProductsController as vmProducts" ng-cloak class="col-md-12">
      <div st-table="vmProducts.stFilteredProducts"
        st-safe-src="vmProducts.products">
        <div class="row">
          <div class="col-md-8">
            <h1>Products</h1>
          </div>
          <div class="col-md-4 text-right">
            <input st-search placeholder="Search" class="input-sm form-control b-margin b-bottom" type="search"/>
            <a class="btn btn-primary btn-sm" href="/parts/products/new">New Product</a>
          </div>
        </div>
        <table class="table table-striped">
          <thead>
            <tr>
              <th st-sort="name">Name <i class="fa fa-sort"></i></th>
              <th>Unit Price</th>
              <th>Pricing Type</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tr ng-repeat="product in vmProducts.stFilteredProducts">
            <td>
              {[{ product.name }]}
              <div bestline-api-errors="product.apiErrors"></div>
            </td>
            <td>{[{ product.base_price }]}</td>
            <td>{[{ product.pricing_type }]}</td>
            <td>
              <span ng-hide="product.loading">
                <a class="btn btn-primary btn-xs" href="/parts/products/{[{ product.id }]}">Edit</a>
              </span>
              <span ng-show="product.loading"><i class="fa fa-circle-o-notch fa-spin"></i></span>
            </td>
          </tr>
        </table>
        <div class="row">
            <div class="col-md-12 text-center">
                <div st-pagination st-items-by-page="30" st-displayed-pages="4"></div>
            </div>
        </div>
      </div>
  </div>
@stop