@extends('layout')

@section('stylesheets')
@parent
<link rel="stylesheet" href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css" type="text/css"/>
@stop

@section('javascript')
@parent
<script type="application/javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="application/javascript">
@if(!empty($part->id))
    $(document).ready(function() {
        $('#inventoryTable').dataTable({
      	  "sPaginationType": "full_numbers",
          "bProcessing": false,
          "sAjaxSource": "/api/inventory/part/datatable/{{ $part->id }}",
          "bServerSide": true
        });
        
    });
@endif
</script>
@stop

@section('main')
<div class="col-md-12">
    @if(empty($part->id))
        {{ Form::open(['route' => ['inventory.part.save']]) }}
        <h1>Add new Part <a href="/inventory" class="btn btn-default"><i class="fa fa-fw fa-mail-reply"></i> Back to Inventory</a></h1>
    @else
        {{ Form::open(['route' => ['inventory.part.save', $part->id]]) }}
        <h1>Edit {{ $part->name }} <a href="/inventory" class="btn btn-default"><i class="fa fa-fw fa-mail-reply"></i> Back to Inventory</a></h1>
    @endif
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('name', "Part Name") }}
                {{ Form::text('name', Input::old('name', $part->name), ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('description', "Part Description") }}
                {{ Form::textarea('description', Input::old('description', $part->description), ['class' => 'form-control', 'rows' => 5]) }}
            </div>
            <div class="form-group">
                {{ Form::label('minimum_qty', "Minimum Quantity") }}
                {{ Form::text('minimum_qty', Input::old('minimum_qty', $part->minimum_qty), ['class' => 'form-control']) }}
            </div>
            @if(empty($part->id))
                <div class="form-group">
                    {{ Form::label('quantity', 'Initial Quantity') }}
                    {{ Form::text('quantity', Input::old('quantity', $part->quantity), ['class' => 'form-control']) }}
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <button type="submit" class="btn btn-primary pull-right">
                @if(empty($part->id))
                    <i class="fa fa-fw fa-save"></i> Add Part
                @else
                    <i class="fa fa-fw fa-save"></i> Update Part
                @endif
            </button>
        </div>
    </div>
    {{ Form::close() }}
    @if(!empty($part->id))
        <div class="row">
            <div class="col-md-12">
                <hr/>
                <h3>Inventory Management</h3>
            </div>
        </div>
        {{ Form::open(['route' => ['inventory.part.adjust', $part->id]]) }}
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-2">
                    <div class="form-group">
                        {{ Form::label('adjustment', "Adjustment") }}
                        {{ Form::text('adjustment', Input::old('adjustment'), ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="col-md-10">
                    {{ Form::label('reason', 'Reason') }}
                    <div class="form-group input-group">
                        {{ Form::text('reason', Input::old('reason'), ['class' => 'form-control']) }}
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Add Record</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
        <div class="row">
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
            </table>
        </div>
    @endif
</div>
@stop