<?php

namespace Api;

use \Response;
use \Product;
use \Log;

class ProductController extends BaseController
{
    public function getIndex($id)
    {
        switch($id) {
            case 'list':
                $products = Product::lists('name', 'id');
                return Response::json($products);
        }
        
        $product = Product::find($id);
        
        if(!$product instanceof Product) {
            Log::error("Failed to locate product with ID $id");
            return Response::json("Not Found", 404);
        }
        
        return Response::json($product);
    }
    
    public function getList()
    {
        
    }
}
