<?php

namespace Api\Order;

use \Log;
use \Response;
use \Order\Fabric as OrderFabric;

class FabricController extends \Api\BaseController
{   

    public function deleteIndex($order_id, $fabric_id){ 

      $orderOption = OrderFabric::findOrFail($fabric_id);
      $orderOption->delete();
        
      return Response::json($fabric_id ." Deleted");
    }
}
