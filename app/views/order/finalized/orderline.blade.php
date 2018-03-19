<div class="row form-group order-line">
    <div class="col-md-5">
        <div class="row">
            <div class="col-md-12">
                <h5 class="text-info">Order Line #{{ $index +1 }}</h5>
                <table class="table table-condensed options-table">
                    <thead>
                        <th colspan="3">Style</th>
                        <th>Mount</th>
                    </thead>
                    <row>
                        <td colspan="3">{{ $orderLine->product->name }}</td>
                        <td>{{$orderLine->mount->description}}</td>
                    </row>
                </table>
            </div>
        </div>

        @if($orderLine->width > 0 || $orderLine->height > 0)
            <div class="row">
                <div class="col-md-12"
                    <h5 class="text-info">Shade</h5>
                    <table class="table table-condensed options-table">
                        <thead>
                            <th>Width</th>
                            <th>Height</th>
                            <th>Return</th>
                            <th>Headerboard</th>
                        </thead>
                        <row>
                            <td>{{ $orderLine->width }}</td>
                            <td>{{ $orderLine->height }}</td>
                            <td>{{ $orderLine->return }}</td> 
                            <td>{{$orderLine->headerboard  }}</td>
                        </row>
                    </table>

                    <table class="table table-condensed options-table">
                        <thead>
                            <th>Hardware</th>
                            <th>Pull Type</th>
                            <th>Cord Pos.</th>
                            <th>Cord Len</th>
                        </thead>
                        <row>
                            <td>@if($orderLine->hardware){{$orderLine->hardware->description  }}@endif</td>
                            <td>@if($orderLine->pullType){{$orderLine->pullType->description }}@endif</td>
                            <td>@if($orderLine->cord_position){{$orderLine->cord_position->description }}@endif</td>
                            <td>{{$orderLine->cord_length}}</td>
                        </row>
                    </table>
                </div>
            </div>
        @endif
        
        @if($orderLine->valance_width > 0)
            <div class="row">
                <div class="col-md-12">
                    <h5 class="text-info">Valance</h5>
                    <table class="table table-condensed options-table">
                        <thead>
                            <th>Width</th>
                            <th>Height</th>
                            <th>Return</th>
                            <th>Headerboard</th>
                        </thead>
                        <tr>
                            <td>{{ $orderLine->valance_width }}</td>
                            <td>{{ $orderLine->valance_height }}</td>
                            <td>{{ $orderLine->valance_return }}</td>
                            <td>{{ $orderLine->valance_headerboard }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        @endif
    </div>
    <div class="col-md-5">
        <div class="row">
            <div class="col-md-12">
                <h5 class="text-info">Options</h5>
                <table class="table table-condensed options-table">
                    <thead>
                        <th>Option</th>
                        <th>Sub Option</th>
                        <th>Price</th>
                    </thead>
                    @if($orderLine->options->count() > 0)
                        @foreach($orderLine->options as $option)
                            <tr class="option_row_template">
                                <td style="width:40%" class="option-name">{{ $option->option->name }}</td>
                                <td style="width:40%" class="option-sub">{{ $option->suboption->name }}</td>
                                <td class="option-cost text-right">${{ $option->price }}</td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
        
    </div>
    <div class="col-lg-2">
        <div class="row">
            <div class="col-md-12">
                <h5 class="text-info">Pricing</h5>
                <table class="table table-condensed" id="pricing-table">
                    <thead>
                        <th>Type</th>
                        <th>Price</th>
                    </thead>
                    <tr>
                        <td>Shade</td>
                        <td> <p>${{ $orderLine->finalized->shade_price }}</p> </td>
                    </tr>
                    <tr>
                        <td>Valance</td>
                        <td> <p>${{ $orderLine->finalized->valance_price }}</p> </td>
                    </tr>
                    <tr>
                        <td>Fabric</td>
                        <td> <p>${{ $orderLine->finalized->fabric_price }}</p> </td>
                    </tr>
                    <tr>
                        <td>Options</td>
                        <td> <p>${{ $orderLine->finalized->options_price }}</p> </td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td> <p>${{ $orderLine->finalized->total_price }}</p> </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
