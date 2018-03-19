<?php

namespace Inventory;

use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function getDashboard()
    {
        $fabric = \Fabric::first();
        
        return \View::make('inventory.dashboard');
    }
}