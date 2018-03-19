<?php

namespace Bestline\ExpressionLanguage;

use Bestline\ExpressionLanguage;

class Functions
{
    public function fabricMultiple($fabric_id, $width)
    {
        $fabric = \Fabric::find($fabric_id);

        if(!$fabric instanceof \Fabric) {
            return 1;
        }

        $retval = ceil($width / $fabric->width);

        return $retval;
    }

    public function fabricUnitPrice($fabric_id, $price = null)
    {
        $fabric = \Fabric::find($fabric_id);

        if(!$fabric instanceof \Fabric) {
            return 0;
        }

        if($price > 0) {
            return $price;
        }

        return $fabric->unit_price;
    }

    public function fabricPricingType($fabric_id)
    {
        $fabric = \Fabric::find($fabric_id);

        if(!$fabric instanceof \Fabric) {
            return null;
        }

        return $fabric->pricing_type;
    }

    public function pricingTypeValue($type, $length, $width, $return)
    {
        $result = 1;

        $pricingType = \PricingType::where('type', '=', $type)->first();

        if(!$pricingType instanceof \PricingType) {
            return $result;
        }

        if(!is_null($pricingType->formula)) {
            $ee = new ExpressionLanguage();
            $result = $ee->evaluate($pricingType->formula, compact('length', 'width', 'return'));
        }

        return round($result, 2);
    }

    public function productQuantity($productId, $length, $width, $return)
    {
        $product = \Product::find($productId);

        if(!$product instanceof \Product) {
            return null;
        }

        $pricingType = $product->getPricingType();

        $ee = new ExpressionLanguage();

        return $ee->evaluate($pricingType->formula, compact('length', 'width', 'return'));
    }

    public function companyId($orderId) {

        $order = \Order::find($orderId);

        if(!$order instanceof \Order) {
            return null;
        }

        return $order->company()->first()->id;
    }

    public function markupPercentage($product_id) {
        $product = \Product::find($product_id);

        if(!$product instanceof \Product) {
            return 1;
        }

        return 1 + $product->price_plus_percentage;
    }

    public function productUnitPrice($productId, $companyId)
    {
        $product = \Product::find($productId);

        if(!$product instanceof \Product) {
            return 0;
        }

        $company = \Company::find($companyId);

        if(!$company instanceof \Company) {
            return round($product->base_price, 2);
        }

        $modifications = $company->getCompanyProductPrice($productId);

        if(isset($modifications->base_price) && !is_null($modifications->base_price)) {
            return round($modifications->base_price, 2);
        }

        return round($product->base_price, 2);
    }
}