<?php

namespace Order;

class FinalizedOrder extends \Eloquent {
	
	protected $table = "finalized_orders";

	protected $softDelete = true;
	
	
}