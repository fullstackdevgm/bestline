<?php

namespace Order\Finalized;

class LineItem extends \Eloquent {
	
	protected $table = "finalized_order_lines";
	protected $softDelete = true;


}