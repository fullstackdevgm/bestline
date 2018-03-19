<?php

namespace Api\Order\Fabric;

use \Log;
use \Response;
use \Order\Fabric\Option as OrderFabricOption;

class OptionController extends \Api\BaseController
{   

    public function deleteIndex($order_id, $fabric_id, $option_id){ 

      $orderFabricOption = OrderFabricOption::findOrFail($option_id);
      $orderFabricOption->delete();
        
      return Response::json($option_id ." Deleted");
    }
}
