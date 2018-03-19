<?php

namespace Api\Order\OrderLine;

use \Log;
use \Response;
use \Order\LineItem\Option as OrderLineOption;

class OptionController extends \Api\BaseController
{   

    public function deleteIndex($order_id, $order_line_id, $option_id){ 

      $orderLineOption = OrderLineOption::findOrFail($option_id);
      $orderLineOption->delete();
        
      return Response::json($option_id ." Deleted");
    }
}
