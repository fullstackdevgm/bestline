<?php

namespace Api\Order;

use \Log;
use \Response;
use \Order\Option as OrderOption;

class OptionController extends \Api\BaseController
{   

    public function deleteIndex($order_id, $option_id){ 

      $orderOption = OrderOption::findOrFail($option_id);
      $orderOption->delete();
        
      return Response::json($option_id ." Deleted");
    }
}
