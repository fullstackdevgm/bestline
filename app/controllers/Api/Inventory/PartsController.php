<?php

namespace Api\Inventory;

use \Inventory\Part as InventoryPart;

class PartsController extends \Api\BaseController
{    
    public function getInventoryHistoryDatatable($id)
    {
        $fabricInventory = InventoryPart::where('part_id', '=', $id)->orderBy('created_at', 'asc')->get();
    
        return \Datatable::collection($fabricInventory)
                        ->showColumns('created_at', 'user_full_name', 'pretty_adjustment', 'reason', 'pretty_quantity')
                        ->searchColumns('reason', 'user_full_name')
                        ->orderColumns('created_at', 'user_full_name')
                        ->make();
    }
}