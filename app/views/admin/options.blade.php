<br/><br/><br/>
<div class="container" style="margin-top: 50px">
    <div class="col-md-4">
        <div class="col-md-12" id="optionsTree"></div>
    </div>
    <div class="col-md-8">
        <h1>Details</h1>
        <div class="row">
            <div class="col-md-12">
                <form action="" method="post" id="options_edit_form">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" size="20" id="name" name="option_name" class="form-control" style="height: 34px">
                    </div>
                    <div class="form-group">
                        <label for="pricing_type">Pricing Type</label>
                        <select name="option_pricing_type" id="pricing_type" class="form-control">
                        @foreach(\PricingType::getTypes(true) as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mincharge">Minimum Charge</label>
                        <input type="text" size="5" id="mincharge" name="option_min_charge" class="form-control" style="height: 34px">
                    </div>
                    <div class="form-group">
                        <label for="pricingvalue">Pricing Value</label>
                        <input type="text" size="5" id="pricingvalue" name="option_pricing_value" class="form-control" style="height: 34px">
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="button" id="savebtn" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
