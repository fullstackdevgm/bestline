<?php

use Order\FinalizedOrder;
use Illuminate\Support\Collection;
use Bestline\Event\Events;
use Fabric\Type;
use Bestline\Order\Calculator;
use Carbon\Carbon;
use Order\LineItem as OrderLine;
use Order\LineItem\Option as OrderLineOption;
use Order\Fabric as OrderFabric;
use Order\Option as OrderOptions;

class Order extends Eloquent
{
	protected $table = 'orders';
	public $fabricsUnsaved = [];
	public $optionsUnsaved = [];
	public $orderLinesUnsaved = [];

	static public function boot()
	{
	    static::deleting(array('Order', 'onOrderDelete'));
	    static::created(['Order', 'onOrderCreated']);
	    static::updated(['Order', 'onOrderUpdated']);
	}

	//setup events
	static public function onOrderCreated(\Order $order)
	{
	    \Event::fire(Events::ORDER_CREATED, [\Auth::user(), $order]);
	    return true;
	}

	static public function onOrderUpdated(\Order $order)
	{
	    \Event::fire(Events::ORDER_UPDATED, [\Auth::user(), $order]);
	}

	static public function onOrderDelete(\Order $order)
	{
	    foreach($order->orderLines as $lineItem) {
	        $lineItem->delete();
	    }
	}

	//setup relationships
	public function company()
	{
		return $this->belongsTo('Company', 'company_id');
	}

	public function contact()
	{
		return $this->belongsTo('Contact', 'contact_id');
	}

	public function customerType()
	{
		return $this->belongsTo('CustomerType', 'customer_type_id');
	}

	public function shippingMethod()
	{
		return $this->belongsTo('ShippingMethod', 'shipping_method_id');
	}

	public function shippingAddress()
	{
		return $this->belongsTo('Address',  'shipping_address_id');
	}

	public function billingAddress()
	{
		return $this->belongsTo('Address',  'billing_address_id');
	}

	public function product()
	{
	    return $this->belongsTo('Product', 'product_id');
	}

	public function ringType()
	{
	    return $this->belongsTo('RingType', 'ring_type_id');
	}

	public function fabrics()
	{
	    return $this->hasMany('Order\Fabric', 'order_id');
	}

	public function defaultOptions()
	{
	    return $this->hasMany('Order\Option', 'order_id');
	}

	public function orderLines()
	{
	    return $this->hasMany('Order\LineItem', 'order_id');
	}

	public function alerts(){
		return $this->hasMany('Alert');
	}

	public function finalized(){
		return $this->hasOne('Order\FinalizedOrder');
	}

	//setup magic attributes
	public function getInConfirmationTimeframeAttribute(){
		$now = Carbon::now();
		$updatedDate = Carbon::parse($this->updated_at);
		$confirmationEnd = Carbon::parse($this->updated_at)->addWeek();
		return ($now->between($updatedDate, $confirmationEnd)) ? true : false;
	}

	public function getHeaderboardCoverCutLengthTotalAttribute(){
		$cutLength = 0;

		foreach($this->orderLines as $orderLine) {
			$cutLength = $cutLength + $orderLine->headerboard_cover_cut_length;
		}

		return $cutLength;
	}

	public function getHeaderboardCoverFabricAttribute(){

		if($this->orderLines){
			$hasFirstOrderLine = $this->orderLines[0] instanceof OrderLine;
			if($hasFirstOrderLine){
				return $this->orderLines[0]->headerboard_cover_fabric;
			}
		}

		return null;
	}

	public function getIsShipAttribute(){
		$isShip = false;

		if(isset($this->shippingMethod) && ($this->shippingMethod->name === "FedEx" || $this->shippingMethod->name === "UPS")){
			$isShip = true;
		}
		return $isShip;
	}
	public function getIsFinalizedAttribute(){
		$IsFinalized = false;

		if($this->finalized){
			$IsFinalized = true;
		}
		return $IsFinalized;
	}

	//other functions
	public function finalize(){

		if($this->finalized instanceof FinalizedOrder){
			$finalizedOrder = FinalizedOrder::find($this->finalized->id);
		} else {
			$finalizedOrder = new FinalizedOrder();
		}

		$finalizedOrder->subtotal = $this->subtotal;
		$finalizedOrder->discount_total = $this->discount_total;
		$finalizedOrder->rush_total = $this->rush_total;
		$finalizedOrder->total = $this->total;

	    try {

    	    DB::beginTransaction();

			$this->finalized()->save($finalizedOrder);
			$firstStationId = Station::all()->sortBy("sort_order")->first()->id;
			$this->current_station_id = $firstStationId;

			foreach($this->orderLines as $line) {
			    $finalLine = $line->finalize();
			}

			$this->save();

    	    DB::commit();
	    } catch(\Exception $e) {
	        DB::rollback();
	        throw $e;
	    }

	    return $this;
	}

	public function getDates()
	{
		return parent::getDates() + array('date_received', 'date_due', 'date_shipped');
	}

	public function getNewOrderLineOptions()
	{
		$orderLineOptions = [];

		foreach($this->fabrics as $fabric){
			if(count($fabric->options) > 0){

				$optionArray = $fabric->options()->first()->load('data')->toArray();

				$orderFabricOptionId = $optionArray['id'];
				unset($optionArray['id']);
				if($optionArray['data'] && $optionArray['data']['id']){
					unset($optionArray['data']['id']);
				}

				$newOption = OrderLineOption::getFromInputs($optionArray);
				$newOption->order_fabric_option_id = $orderFabricOptionId;
				$newOption->order_fabric_id = $fabric->id;

				if($newOption->dataUnsaved){
				    $newOption->data = $newOption->dataUnsaved->toArray();
				} else {
					$newOption->data = null;
				}

				$orderLineOptions[] = $newOption;
			}
		}
		foreach($this->defaultOptions as $orderOption){

			$optionArray = $orderOption->load('data')->toArray();

			unset($optionArray['id']);
			if($optionArray['data'] && $optionArray['data']['id']){
				unset($optionArray['data']['id']);
			}

			$newOption = OrderLineOption::getFromInputs($optionArray);
			$newOption->order_option_id = $orderOption->id;

			if($newOption->dataUnsaved){
				$newOption->data = $newOption->dataUnsaved->toArray();
			} else {
				$newOption->data = null;
			}

			$orderLineOptions[] = $newOption;
		}

		return $orderLineOptions;
	}

	public function fabricByType($typeString)
	{
	    foreach($this->fabrics as $fabric) {
	        if($fabric->type->type == $typeString) {
	            return $fabric;
	        }
	    }

	    return null;
	}

	static public function getFromInputsStep1($inputs)
	{
		//validate input
		$validator = Validator::make($inputs, Order::getValidationRules());
		if($validator->fails()) {throw new Exception('You\'re missing some required data. Error:' . $validator->messages()->first());}

	    //find order or create order
    	if(isset($inputs['id'])){
    		$order = Order::find($inputs['id']);

    		if(!$order instanceof Order) {
    		    $order = new Order;
    		}
    	} else {
    		$order = new Order;
    	}

	    //find associated company if any
	    $company = Company::find($inputs['company_id']);
	    if($company instanceof Company) {
	    	$order->credit_terms = $company->credit_terms;
	    	$order->company_id = $company->id;
	    }

		$order->contact_id = isset($inputs['contact_id']) ? $inputs['contact_id'] : NULL;
		$order->product_id = isset($inputs['product_id']) ? $inputs['product_id'] : NULL;
		$order->ring_type_id = isset($inputs['ring_type_id'])? $inputs['ring_type_id'] : NULL;
		$order->notes = isset($inputs['notes'])? $inputs['notes'] : NULL;
		$order->ticket_notes = isset($inputs['ticket_notes'])? $inputs['ticket_notes'] : NULL;
		$order->invoice_notes = isset($inputs['invoice_notes'])? $inputs['invoice_notes'] : NULL;
		$order->sidemark = isset($inputs['sidemark'])? $inputs['sidemark'] : NULL;
		$order->purchase_order = isset($inputs['purchase_order'])? $inputs['purchase_order'] : NULL;
		$order->is_quote = isset($inputs['is_quote'])? $inputs['is_quote'] : 0;

		//set billing address
		$billingAddressId = isset($inputs['billing_address_id'])? $inputs['billing_address_id'] : NULL;
		$billingAddress = Address::find($billingAddressId);
		if(!$billingAddress instanceof Address){
			throw new Exception("Your billing address was not found.");
		}
		$order->billingAddress()->associate($billingAddress);

		//set shipping address
		if (isset($inputs['copyBilling']) && $inputs['copyBilling'] === true) {
			$shippingAddress = $billingAddress;
		} else {
			$shippingAddressId = isset($inputs['shipping_address_id'])? $inputs['shipping_address_id'] : NULL;
			$shippingAddress = Address::find($shippingAddressId);
			if(!$shippingAddress instanceof Address){

				throw new Exception("Your shipping address was not found.");
			}
		}
		$order->shippingAddress()->associate($shippingAddress);
		$order->shipping_method_id = $shippingAddress->shipping_method_id;

		//set dates
		if(isset($inputs['date_received'])){
			$order->date_received = date('Y-m-d', strtotime($inputs['date_received']));
		}
		if(isset($inputs['date_due'])){
			$order->date_due = date('Y-m-d', strtotime($inputs['date_due']));
		}

		foreach($inputs['fabrics'] as $fabric){

			$order->fabricsUnsaved[] = OrderFabric::getFromInputs($fabric);
		}

		foreach($inputs['default_options'] as $defaultOptions){

			$order->optionsUnsaved[] = OrderOptions::getFromInputs($defaultOptions);
		}

		return $order;
	}

	static public function createFromOrderData($inputs)
	{
	    //find order
	    $order = static::find($inputs['id']);
	    if(!$order instanceof static) {
	        throw new \Exception("We failed to locate this order. Please try again.");
	    }

	    //check product data
	    $productIdIsset = (isset($inputs['product_id']) && !empty($inputs['product_id']));
	    $hasValidProduct = false;
	    if($productIdIsset){
	    	$order->product_id =  $inputs['product_id'];

	    	$product = Product::find($inputs['product_id']);
	    	$hasValidProduct = ($product instanceof Product);
	    	if(!$hasValidProduct) {
	    		throw new Exception("We failed to locate the product. Please try again.");
	    	} else {

	    		//check that all items have height_adjustment_option_id
	    		foreach($inputs['order_lines'] as $line) {
	    			if($product->is_poufy){
	    				if($line['height_adjustment_option_id'] === ""){
	    					throw new Exception("All poufy order items need an adjustment.");
	    				}
	    			}
	    		}
	    	}
	    }

	    $order->purchase_order = $inputs['purchase_order'];
	    $order->deposit_check_no = $inputs['deposit_check_no'];
	    $order->deposit_amount = $inputs['deposit_amount'];
	    $order->discount_percent = $inputs['discount_percent'];
	    $order->is_rush = $inputs['is_rush'];
	    $order->rush_percent = ($order->is_rush)? $inputs['rush_percent'] : 0;
	    $order->boxing_cost = $inputs['boxing_cost'];
	    $order->shipping_amount = $inputs['shipping_amount'];
	    $order->shipping_method_id = $inputs['shipping_method_id'];

	    if(isset($inputs['ring_type_id']) && !empty($inputs['ring_type_id'])){
	    	$order->ring_type_id = $inputs['ring_type_id'];
	    }

	    foreach($inputs['order_lines'] as $line) {

	    	$orderLine = OrderLine::getLineItemFromInputs($line);
	    	$order->orderLinesUnsaved[] = $orderLine;

	        if(isset($line['options']) && is_array($line['options'])) {
	            foreach($line['options'] as $lineOption) {
	                $orderLine->optionsUnsaved[] = OrderLineOption::getFromInputs($lineOption);
	            }
	        }
	    }

	    return $order;
	}

	static public function getValidationRules()
	{
		$rules = array(
			'company_id' => 'required|exists:companies,id',
			'sidemark' => 'required|min:3',
			'date_received' => 'required|date'
		);

		return $rules;
	}

	public function getSidetabsAttribute(){

	    $headerBoards = [];
	    $sideTabs = [];
	    foreach($this->order_lines as $index=>$orderLine) {

	        //make sure a sidetab option is found
	        $foundSidetab = false;
	        foreach($orderLine->options as $option){

	            if($option->option->name === "Side Tabs"){
	                $foundSidetab = true;
	            }
	        }
	        if(!$foundSidetab){
	            continue; //skip the rest of this loop
	        }

	        //add sidetab
	        $orderLineLabel = "Item ". ($index + 1);
	        $isHeaderboardNew = !in_array($orderLine->headerboard, $headerBoards);
	        if($isHeaderboardNew){
	            array_push($headerBoards, $orderLine->headerboard);

	            $sideTabs[$orderLine->headerboard] = (object)[
	                'items'=> [$orderLineLabel],
	                'height' => $orderLine->sidetab_height,
	                'width' => $orderLine->sidetab_width,
	            ];
	        }

	        if(!$isHeaderboardNew){
	            array_push($sideTabs[$orderLine->headerboard]->items, $orderLineLabel);
	        }
	    }

	    return (!empty($sideTabs))? $sideTabs : null;
	}

	public function calculateTotals(){

		$this->subtotal = 0;
		foreach($this->orderLines as $orderLine) {
		    $this->subtotal += $orderLine->total_price;
		}

		$this->discount_total = number_format(round($this->subtotal * ($this->discount_percent / 100), 2), 2, '.', '');
		$this->rush_total = number_format(round($this->subtotal * ($this->rush_percent / 100), 2), 2, '.', '');
		$this->total = $this->subtotal - $this->discount_total + $this->rush_total + $this->shipping_amount + $this->boxing_cost - $this->deposit_amount;
	}

	public function reviewOrderForAlerts($isStep2 = true){

		$this->alerts()->delete();
		$alerts = [];

		//due date must be set and be after current moment
		$dueDate = Carbon::parse($this->date_due);
		$dueDateIsNotSet = !$this->date_due;
		$dueDateIsNotInFuture = !$dueDate->isFuture();
		if($dueDateIsNotSet || $dueDateIsNotInFuture){
			$newAlert = new Alert;
			$newAlert->slug = 'order-needs-future-due-date';
			$newAlert->description = 'Order/quote needs future due date';
			$newAlert->blocks_finalization = true;
			$alerts[] = $newAlert;
		}

		//check for orderlines
		if($this->order_lines->isEmpty()){
			$newAlert = new Alert;
			$newAlert->slug = 'order-needs-lines';
			$newAlert->description = 'Order needs line items.';
			$newAlert->blocks_finalization = true;
			$alerts[] = $newAlert;
		}

		//check each orderline
		foreach($this->order_lines as $orderLine){

			//check for mount position
			if(!$orderLine->mount_id){
				$newAlert = new Alert;
				$newAlert->slug = 'orderline-needs-mount';
				$newAlert->description = 'Order line needs mount.';
				$newAlert->order_line_id = $orderLine->id;
				$newAlert->blocks_finalization = true;
				$alerts[] = $newAlert;
			}

			//check that order line has an adjustment if product is poufy
			if($this->product->is_poufy && !$orderLine->height_adjustment_option_id){
				$newAlert = new Alert;
				$newAlert->slug = 'poufy-orderline-needs-adjustment';
				$newAlert->description = 'Poufy order line needs adjustment.';
				$newAlert->order_line_id = $orderLine->id;
				$newAlert->blocks_finalization = true;
				$alerts[] = $newAlert;
			}

			//check that order line has cord length if pull type is set
			if($orderLine->pull_type_id && (!$orderLine->cord_length || $orderLine->cord_length === 0)){
				$newAlert = new Alert;
				$newAlert->slug = 'orderline-needs-cord-length-if-has-pull-type';
				$newAlert->description = 'Order line needs cord length if it has a pull type.';
				$newAlert->order_line_id = $orderLine->id;
				$newAlert->blocks_finalization = true;
				$alerts[] = $newAlert;
			}

			// some alerts only trigger if orderline has a shade
			if($orderLine->has_shade){

				//check that order line has pull type
				if(!$orderLine->pull_type_id){
					$newAlert = new Alert;
					$newAlert->slug = 'orderline-needs-pull-type';
					$newAlert->description = 'Order line needs a pull type.';
					$newAlert->order_line_id = $orderLine->id;
					$newAlert->blocks_finalization = true;
					$alerts[] = $newAlert;
				}

				//check that order line has either a headerboard value or a valance_headerboard value
				if(!floatval($orderLine->headerboard) > 0 && !floatval($orderLine->valance_headerboard) > 0){
					$newAlert = new Alert;
					$newAlert->slug = 'orderline-needs-headerboard';
					$newAlert->description = 'Order line needs a headerboard value.';
					$newAlert->order_line_id = $orderLine->id;
					$newAlert->blocks_finalization = true;
					$alerts[] = $newAlert;
				}
			}

			//some alerts only trigger only if order line has both a shade and a valance
			$hasShadeAndValance = ($orderLine->has_shade && $orderLine->has_valance)? true : false;
			if($hasShadeAndValance){
				$hasAttachedValance = $orderLine->checkForSubOption('Attached Valance');
				$shadeHasHeaderboard = $orderLine->headerboard > 0;
				if(!$hasAttachedValance && $shadeHasHeaderboard){
					$newAlert = new Alert;
					$newAlert->slug = 'no-shade-headerboard-for-non-attached-valance';
					$newAlert->description = 'If an order line does not have an attached valance, the shade headerboard should not be set.';
					$newAlert->order_line_id = $orderLine->id;
					$newAlert->blocks_finalization = true;
					$alerts[] = $newAlert;
				}
			}
		}

		$this->alerts()->saveMany($alerts);
	}
}
