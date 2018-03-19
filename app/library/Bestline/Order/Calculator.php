<?php

namespace Bestline\Order;

use Bestline\ExpressionLanguage;
use Lookups\Hardware;
use Order\LineItem;
use Order\LineItem\Option as OrderLineOption;
use \Order\Finalized\LineItem as FinalizedLineItem;
use \Option;
use Fabric\Type;
use \Log;
use \PricingType;
use \Fabric;
use \Order\Fabric as OrderFabric;
use \Order\Finalized\Fabric as OrderFinalizedFabric;
use Bestline\Math\Round;
use stdClass;

class Calculator
{
    static public function calculateOrderLine(LineItem $lineItem){

        $lineItem->shade_price = static::calculateShadePrice($lineItem);
        $lineItem->valance_price = static::calculateValancePrice($lineItem);
        $lineItem->fabric_price = static::calculateFabricPrice($lineItem);
        $lineItem->options_price = static::calculateOptionPrice($lineItem);
        $lineItem->total_price = $lineItem->shade_price + $lineItem->valance_price + $lineItem->fabric_price + $lineItem->options_price;

        foreach($lineItem->options as $orderLineOption){
            $orderLineOption->price = static::calculatePriceForOption($lineItem, $orderLineOption);
        }

        return $lineItem;
    }

    static public function calculateLengthMetrics(LineItem $lineItem){

        //setup cache to only call this once
        if($lineItem->lengthMetricsCache){
            return $lineItem->lengthMetricsCache;
        } else {
            $lineItem->lengthMetricsCache = true;
            $panelHeight = 0;
            $skirtHeight = 0;
            $totalPanels = 0;
            $manufacturingLength = (float) $lineItem->height;
            $skirtEmbellishmentHeight = 0;
        }

        //line item variables needed for calculation
        $height = $lineItem->height;
        $heightAdjustmentOptionId = $lineItem->height_adjustment_option_id;
        $tdbuDeduction = $lineItem->tdbu_deduction;
        $options = $lineItem->options;
        $numExtraPanels = $lineItem->num_extra_panels;
        $panelHeightOverride = $lineItem->product->panel_height_override;
        $panelSkirtOverride = $lineItem->product->panel_skirt_override;
        $panelHeightMin = $lineItem->product->panel_height_min;
        $panelHeightMax = $lineItem->product->panel_height_max;
        $embellishmentOption = $lineItem->embellishment_option;

        //find length conditionals
        $hasPoufyHeightAdjustment = LineItem::isHeightAdjusted($heightAdjustmentOptionId);
        $hasTdbuDeduction = ($tdbuDeduction)? true : false;

        //check for embellishment
        if($embellishmentOption && $embellishmentOption->data){
            $embellishmentSizeBottom = ($embellishmentOption->data->size_bottom)? $embellishmentOption->data->size_bottom : 0;
            $embellishmentInsetSizeBottom = ($embellishmentOption->data->inset_size_bottom)? $embellishmentOption->data->inset_size_bottom : 0;
            $skirtEmbellishmentHeight = $embellishmentSizeBottom + $embellishmentInsetSizeBottom;
        }

        //poufy height adjustment sets all length items manually
        if($hasPoufyHeightAdjustment){

            //set panel height to 5" for poufy styles with height adjustment
            $panelHeight = (float) ($panelHeightOverride)? $panelHeightOverride : 5;
            $skirtHeight = (float) ($panelSkirtOverride)? $panelSkirtOverride : 2;
            $totalPanels = (int) round($height / $panelHeight) + $numExtraPanels;

            $manufacturingLength = $panelHeight * $totalPanels + $skirtHeight;
        }

        //if has tdbu_deduction
        if($hasTdbuDeduction){
            $manufacturingLength = $manufacturingLength - (float) $tdbuDeduction;
        }

        //if has attached valance
        $hasAttachedValance = $lineItem->checkForSubOption('Attached Valance');
        if($hasAttachedValance){
            $manufacturingLength = $manufacturingLength - 0.75;
        }

        if(!$hasPoufyHeightAdjustment){

            $heightForPanelCalc = $manufacturingLength;
            $min = ($panelHeightMin)? $panelHeightMin : 7;
            $max = ($panelHeightMax)? $panelHeightMax : 9.25;

            if($skirtEmbellishmentHeight > 0){
                $heightForPanelCalc = $heightForPanelCalc - $skirtEmbellishmentHeight;
            }

            //calculate panel height to get it approximately equal 50%
            if($heightForPanelCalc > 28){
                $panelOptions = [];
                $arrayOfModifiedPanelSizes = [];
                $arrayOfModifiedSkirtToPanelDifferences = [];
                for($panelSize = $max; $panelSize >= $min; $panelSize -= 0.125) {

                    $numPanelsRaw = $heightForPanelCalc / $panelSize;

                    $panelOption = new stdClass;
                    $panelOption->panelSize = $panelSize;
                    $panelOption->panelCount = floor(Round::toEighth($numPanelsRaw));
                    $panelOption->skirtHeight = $heightForPanelCalc - ($panelOption->panelCount * $panelOption->panelSize);
                    $skirtToPanelPercentage  = ($panelOption->skirtHeight / $panelOption->panelSize) * 100;

                    $skirtIsWithinValidRange = ($skirtToPanelPercentage > 45 && $skirtToPanelPercentage < 57);
                    $skirtIsGreaterThan45Percent = ($skirtToPanelPercentage > 45);

                    if($skirtIsWithinValidRange){
                        $arrayOfModifiedPanelSizes[] = $panelSize;
                    } else { //set size to remove it from valid options
                        $arrayOfModifiedPanelSizes[] = 0;
                    }

                    $skirtToPanelDifferenceFrom50Percent = abs($skirtToPanelPercentage - 50);
                    if($skirtIsGreaterThan45Percent){
                        $arrayOfModifiedSkirtToPanelDifferences[] = $skirtToPanelDifferenceFrom50Percent;
                    } else { //discard panel option
                        $arrayOfModifiedSkirtToPanelDifferences[] = 1000;
                    }

                    $panelOptions[] = $panelOption;
                }

                $foundValidPanels = array_sum($arrayOfModifiedPanelSizes) > 0;
                if($foundValidPanels){
                    $valueOfLargestPanelSize = max($arrayOfModifiedPanelSizes);
                    $indexOfLargestPanelSize = array_search($valueOfLargestPanelSize, $arrayOfModifiedPanelSizes);
                    $bestPanelOption = $panelOptions[$indexOfLargestPanelSize];
                } else {
                    $valueOfSmallestSkirtToPanelDifference = min($arrayOfModifiedSkirtToPanelDifferences);
                    $indexOfOptimalSkirtToPanelDifference = array_search($valueOfSmallestSkirtToPanelDifference, $arrayOfModifiedSkirtToPanelDifferences);
                    $bestPanelOption = $panelOptions[$indexOfOptimalSkirtToPanelDifference];
                }
            } else {
                $bestPanelOption = new stdClass;
                $bestPanelOption->panelCount = 3;
                $bestPanelOption->panelSize = Round::toEighth($heightForPanelCalc / $bestPanelOption->panelCount * 0.86);
                $bestPanelOption->skirtHeight = $heightForPanelCalc - ($bestPanelOption->panelCount * $bestPanelOption->panelSize);
            }

            $totalPanels = $bestPanelOption->panelCount;
            $skirtHeight = $bestPanelOption->skirtHeight;
            $panelHeight = $bestPanelOption->panelSize;

            $skirtHeight = $skirtHeight + $skirtEmbellishmentHeight;
        }

        //package values for result
        $lineItem->lengthMetricsCache = new \stdClass;
        $lineItem->lengthMetricsCache->panel_height = $panelHeight;
        $lineItem->lengthMetricsCache->total_panels = $totalPanels;
        $lineItem->lengthMetricsCache->skirt_height = $skirtHeight;
        $lineItem->lengthMetricsCache->manufacturing_length = $manufacturingLength;

        return $lineItem->lengthMetricsCache;
    }

    static public function calculateWidthMetrics(LineItem $lineItem){

        //setup cache to only call this once
        if($lineItem->widthCache){
            return $lineItem->widthCache;
        } else {
            $manufacturingWidth = (float) $lineItem->width;
            $totalRingColumns = 0;
            $ringSpacing = 1;
        }

        //set line item variables
        $ringFromEdge = $lineItem->product->ring_from_edge;
        $ringMinimum = ($lineItem->product->ring_minimum)? $lineItem->product->ring_minimum : 2;
        $ringDivisor = ($lineItem->product->ring_divisor && $lineItem->product->ring_divisor > 0)? $lineItem->product->ring_divisor : 1;
        $isDogear = $lineItem->product->shape === "Dog Ear";
        $isSquare = ($lineItem->product->shape === 'Square' || $lineItem->product->shape === 'Square/Trapezoid' || $lineItem->product->shape === 'TDBU/BU');

        //calculate manufacturing width
        if($lineItem->return > 0){
            $widthAdjustment = $lineItem->return * 2;
            $manufacturingWidth = round($lineItem->width + $widthAdjustment, 2);
        }

        //determine correct width for column calculation
        $widthMinusOuterRings = $manufacturingWidth - $ringFromEdge * 2;
        $widthForColumnCalc = (!$isDogear)? $widthMinusOuterRings : $lineItem->width;

        //calculate total ring columns and spacing
        $totalRingColumns = ceil($widthForColumnCalc / $ringDivisor) + 1;
        if($totalRingColumns < $ringMinimum) {
            $totalRingColumns = $ringMinimum;
        }
        $totalRingColumns = (int) $totalRingColumns;

        //calculate ring spacing
        $shapeColumns = $totalRingColumns - 1;
        $ringSpacing = round($widthForColumnCalc / $shapeColumns, 3);

        //adjust ring columns for dogear
        if($isDogear){
            $totalRingColumns = $totalRingColumns - 1;
        }

        //package values for result
        $lineItem->widthCache = new \stdClass;
        $lineItem->widthCache->manufacturing_width = $manufacturingWidth;
        $lineItem->widthCache->total_ring_columns = (int) $totalRingColumns;
        $lineItem->widthCache->ring_spacing = (float) $ringSpacing;

        return $lineItem->widthCache;
    }

    static public function calculateShadePrice(LineItem $lineItem)
    {
        $shadePrice = 0;
        $product = $lineItem->product;
        $itemHasProduct = $product instanceof \Product;
        $hardware = $lineItem->hardware;
        $hardwareId = $lineItem->hardware_id;

        if(!$lineItem->has_shade){
            return $shadePrice;
        }

        //find shade price
        if($itemHasProduct) {

            $companyPrice = static::getProductCompanyPrice($lineItem, $product);
            $hasCompanyPricingType = $companyPrice && !is_null($companyPrice->pricing_type);
            $pricingType = ($hasCompanyPricingType)? $companyPrice->getPricingType(): $product->getPricingType();
            $pricingWidth = $lineItem->width + $lineItem->return * 2;
            $pricingLength = $lineItem->height;
            if($pricingType->type == PricingType::TYPE_SQFT_ACTUAL){
                $lineItem->pricing_width = $pricingWidth;
                $lineItem->pricing_length = $pricingLength;
            } else {
                $lineItem->pricing_width = Round::toNearestSixInches($pricingWidth);
                $lineItem->pricing_length = Round::toNearestSixInches($pricingLength);
            }
            $productQuantity = static::pricingTypeMultiplier($lineItem, $pricingType);
            $productUnitPrice = static::productUnitPrice($lineItem, $product, $companyPrice);
            $markupPercentage = static::markupPercentage($product);

            //minimum quantity is 12 sqft
            if($productQuantity < 12){
                $productQuantity = 12;
            }

            $shadePrice = $productQuantity * $productUnitPrice * $markupPercentage;
        }

        //add hardware price to shade price
        if(!is_null($hardwareId)) {
            if(($hardware instanceof Hardware) && !empty($hardware->formula)) {
                $ee = new ExpressionLanguage();
                $shadePrice += $ee->evaluate($hardware->formula, $lineItem);
            }
        }

        return round($shadePrice, 2);
    }

    static public function calculateValancePrice(LineItem $orderLine)
    {
        //to do: need to somehow check if we're using bestline or com fabrics

        // valance_type is required for this calculation
        if(!$orderLine->valance_type){
            return 0;
        }
        // return zero if no valance
        if(!$orderLine->has_valance){
            return 0;
        }

        $valancePrice = 0;
        $pricePerRunningFoot = 0;
        $pricingType = PricingType::where('type', '=', 'w1')->firstOrFail();
        $fabricWidth = $orderLine->valance_width + ($orderLine->valance_return * 2);
        $valanceLength = $orderLine->valance_height;
        $valanceHeaderboard = $orderLine->valance_headerboard;
        $valanceWidth = $orderLine->valance_width;
        $hasReturns = $orderLine->valance_return > 0;
        $isFlat = $orderLine->valance_type->type === 'slug_flat';
        $isPleated = $orderLine->valance_type->type === 'slug_box_pleated';
        $isScalloped = $orderLine->valance_type->type === 'slug_scalloped';
        $orderLine->pricing_length = null;
        $orderLine->pricing_width = $fabricWidth;
        $pricingTypeMultiplier = static::pricingTypeMultiplier($orderLine, $pricingType);

        if($pricingTypeMultiplier < 5){
            $pricingTypeMultiplier = 5;
        }

        if($valanceLength < 6.0001){
            if($isFlat){
                $pricePerRunningFoot = 7;
            } else if($isPleated){
                $pricePerRunningFoot = 8.5;
            } else if($isScalloped){
                $pricePerRunningFoot = 20;
            }
        } else if($valanceLength < 12.0001) {
            if($isFlat){
                $pricePerRunningFoot = 9;
            } else if($isPleated){
                $pricePerRunningFoot = 12;
            } else if($isScalloped){
                $pricePerRunningFoot = 20;
            }
        } else if($valanceLength > 24.0001) {
            if($isFlat){
                $pricePerRunningFoot = 12;
            } else if($isPleated){
                $pricePerRunningFoot = 15;
            } else if($isScalloped){
                $pricePerRunningFoot = 20;
            }
        }

        $valancePrice = $pricingTypeMultiplier * $pricePerRunningFoot;

        return $valancePrice;
    }

    static public function calculateOptionPrice(LineItem $lineItem)
    {
        $retval = 0;

        foreach($lineItem->options as $orderLineOption) {
            $retval += static::calculatePriceForOption($lineItem, $orderLineOption);
        }

        return $retval;
    }

    static public function calculatePriceForOption(LineItem $lineItem, OrderLineOption $orderLineOption)
    {
        $optionPrice = 0;
        $optionTotal = 0;
        $option = $orderLineOption->option;
        $hasOption = $option instanceof Option;
        $subOption = $orderLineOption->subOption;
        $hasSubOption = $subOption instanceof Option;

        if(!$hasOption || !$hasSubOption){
            return $optionTotal;
        }

        $companyPrice = static::getOptionCompanyPrice($lineItem, $subOption);
        $hasCompanyPricingType = $companyPrice && !is_null($companyPrice->pricing_type);
        $pricingType = ($hasCompanyPricingType)? $companyPrice->getPricingType(): $subOption->getPricingType();
        $optionHasPricingType = $pricingType instanceof PricingType;

        if(!$optionHasPricingType) {
            return $optionTotal;
        }


        $optionPrice = static::getOptionPricingValue($subOption, $companyPrice);

        if($option->price_as_fabric || $subOption->price_as_fabric){

            $orderFabric = $orderLineOption->order_fabric;
            $cuts = static::fabricCuts($lineItem, $orderFabric);
            $cutLength = static::fabricCutLength($lineItem, $orderFabric);
            $orderLineOption->pricing_length = $cutLength * $cuts;
            $orderLineOption->pricing_width = $lineItem->width + $lineItem->return * 2;
        } else {
            $orderLineOption->pricing_length = $lineItem->height;
            $orderLineOption->pricing_width = $lineItem->width + $lineItem->return * 2;
        }

        $pricingTypeMultiplier = static::pricingTypeMultiplier($orderLineOption, $pricingType);

        //find option price
        switch ($pricingType->type) {
            case PricingType::TYPE_PERCENT:

                $shadePrice = static::calculateShadePrice($lineItem);
                $optionPriceAsPercent = $optionPrice; //if pricing type is percent, pricing value should be between 0 and 1
                $optionTotal = $shadePrice * $optionPrice;
            break;
            case PricingType::TIER:

                $hasCompanyTierFormula = $companyPrice && !is_null($companyPrice->tier_formula);
                $tierFormula = ($hasCompanyTierFormula)? $companyPrice->tier_formula : $subOption->tier_formula;
                $tierHash = unserialize($tierFormula);
                // start with the min charge for the option

                foreach ($tierHash as $maxWidth => $price) {
                    if ($lineItem->width <= $maxWidth) {
                        $optionPrice = $price;
                        break;
                    }
                }
                $optionTotal = $pricingTypeMultiplier * $optionPrice;
            break;
            default:
                $optionTotal = $pricingTypeMultiplier * $optionPrice;
        }

        // check min price
        $minCharge = static::getOptionMinCharge($subOption, $companyPrice);
        if($optionTotal < $minCharge){
            $optionTotal = $minCharge;
        }

        return $optionTotal;
    }
    static private function getOptionMinCharge($subOption, $companyPrice){

        if($companyPrice && isset($companyPrice->min_charge) && !is_null($companyPrice->min_charge)) {
            return round($companyPrice->min_charge, 2);
        }

        return round($subOption->min_charge, 2);
    }
    static private function getOptionPricingValue($subOption, $companyPrice){

        if($companyPrice && isset($companyPrice->price) && !is_null($companyPrice->price)) {
            return round($companyPrice->price, 2);
        }

        return round($subOption->pricing_value, 2);
    }

    static private function getOptionCompanyPrice(LineItem $lineItem, $subOption){

        $company = $lineItem->order->company;
        $hasCompany = $company instanceof \Company;

        if($hasCompany) {
            $companyPrice = $company->getCompanyOptionPrice($subOption->id);

            if($companyPrice) {
                return $companyPrice;
            }
        }

        return null;
    }

    static public function calculateFabricPrice(LineItem $lineItem){

        $fabricPriceTotal = 0;
        $orderFabrics = $lineItem->order->fabrics;


        //loop through each fabric and add its price to the total
        foreach($orderFabrics as $orderFabric) {

            //skip embellishment for now, per Jill request
            if($orderFabric->type->type === 'embellishment'){
                continue;
            }

            //alter height to account for cuts
            $cuts = static::fabricCuts($lineItem, $orderFabric);
            $cutLength = static::fabricCutLength($lineItem, $orderFabric);
            $orderFabric->pricing_length = $cutLength * $cuts;
            $orderFabric->pricing_width = $orderFabric->fabric->width;

            //find the multiple based on pricing type
            $fabric = $orderFabric->fabric;
            $companyPrice = static::getFabricCompanyPrice($lineItem, $fabric);
            $hasCompanyPricingType = $companyPrice && !is_null($companyPrice->pricing_type);
            $pricingType = ($hasCompanyPricingType)? $companyPrice->getPricingType(): $fabric->getPricingType();
            $fabricPricingTypeMultiplier = static::pricingTypeMultiplier($orderFabric, $pricingType);

            $price = static::fabricUnitPrice($lineItem, $fabric, $companyPrice);
            $fabricPrice = $fabricPricingTypeMultiplier * $price;
            $fabricPriceTotal += $fabricPrice;
        }

        return $fabricPriceTotal;
    }

    static private function pricingTypeMultiplier($input, $pricingType){

        $width = isset($input->pricing_width)? $input->pricing_width : 1;
        $length = isset($input->pricing_length)? $input->pricing_length : 1;

        switch ($pricingType->type) {
            case PricingType::TYPE_SQFT:
            case PricingType::TYPE_SQFT_ACTUAL:
                $pricingTypeMultiplier = ceil($length * $width / 144);
                break;
                break;
            case PricingType::TYPE_LINEAR:
                $pricingTypeMultiplier = ceil($length / 12);
                break;
            case PricingType::TYPE_FLAT:
                $pricingTypeMultiplier = 1;
                break;
            case PricingType::TYPE_L2W1:
                $pricingTypeMultiplier = ceil($length /12) * 2 + ceil($width / 12);
                break;
            case PricingType::TYPE_W1:
                $pricingTypeMultiplier = ceil($width / 12);
                break;
            case PricingType::TYPE_L2:
                $pricingTypeMultiplier = ceil(($length * 2) / 12);
                break;
            case PricingType::TYPE_L1:
                $pricingTypeMultiplier = ceil(($length) / 12);
                break;
            case PricingType::TYPE_YARD:
                $pricingTypeMultiplier = ceil($length / 36);
                break;
            case PricingType::TYPE_L2W2:
                $pricingTypeMultiplier = ceil($length /12) * 2 + ceil($width /12) * 2;
                break;
            case PricingType::TYPE_PERCENT:
                $pricingTypeMultiplier = 1;
                break;
            case PricingType::TIER:
                $pricingTypeMultiplier = 1;
                break;
            default:
                $pricingTypeMultiplier = 1;
        }

        return $pricingTypeMultiplier;
    }

    static private function getProductCompanyPrice(LineItem $lineItem, $product){

        $company = $lineItem->order->company;
        $hasCompany = $company instanceof \Company;

        if($hasCompany) {
            $companyPrice = $company->getCompanyProductPrice($product->id);

            if($companyPrice) {
                return $companyPrice;
            }
        }

        return null;
    }

    static private function productUnitPrice(LineItem $lineItem, $product, $companyPrice){

        if($companyPrice && isset($companyPrice->price) && !is_null($companyPrice->price)) {
            return round($companyPrice->price, 2);
        }

        return round($product->base_price, 2);
    }

    static private function markupPercentage($product) {

        if($product instanceof \Product && $product->price_plus_percentage) {
            return 1 + $product->price_plus_percentage;
        }

        return 1;
    }

    static public function fabricCuts(LineItem $lineItem, OrderFabric $orderFabric)
    {
        $fabricTypeIsForValance = $orderFabric->type->isForValance();

        if($fabricTypeIsForValance){
            $cuts = static::calculateValanceFabricCuts($lineItem, $orderFabric);
        } else {
            $cuts = static::calculateShadeFabricCuts($lineItem, $orderFabric);
        }

        return $cuts;
    }

    static private function calculateValanceFabricCuts(LineItem $lineItem, OrderFabric $orderFabric){

        //find all the needed values to calculate
        $adjustedManufacturingWidth = $lineItem->valance_width;
        $fabric = $orderFabric->fabric;

        //calculate cuts
        if($fabric->width > 0) {
            $cuts = ceil($adjustedManufacturingWidth / $fabric->width);
        } else {
            throw new \Exception("Calculate failed because the fabric \"$fabric->name\" has a width equal to zero.");
        }

        return $cuts;
    }

    static private function calculateShadeFabricCuts(LineItem $lineItem, OrderFabric $orderFabric){

        //find all the needed values to calculate
        $adjustedManufacturingWidth = $lineItem->manufacturing_width;
        $orderFabricType = $orderFabric->type->type;
        $fabric = $orderFabric->fabric;

        //make sure product is set
        if(!$lineItem->product){
            return 1;
        }

        //alter width
        if($lineItem->product->width_plus_lining && ($orderFabricType === Type::TYPE_LINING || $orderFabricType === Type::TYPE_INTERLINING)){
            $adjustedManufacturingWidth = $adjustedManufacturingWidth + $lineItem->product->width_plus_lining;
        } else if($lineItem->product->width_plus){
            $adjustedManufacturingWidth = $adjustedManufacturingWidth + $lineItem->product->width_plus;
        } else if($lineItem->product->width_times) {
            $adjustedManufacturingWidth = $adjustedManufacturingWidth * $lineItem->product->width_times;
        }

        //alter if is embellishment
        if($orderFabricType === Type::TYPE_EMBELLISHMENT){
            return 1; //temporarily setting to one till we decide how to deal with option types, bb
        }

        //calculate cuts
        if($fabric->width > 0) {
            $cuts = ceil($adjustedManufacturingWidth / $fabric->width);
        } else {
            throw new \Exception("Calculate failed because the fabric \"$fabric->name\" has a width equal to zero.");
        }

        return $cuts;
    }

    static public function fabricCutLength(LineItem $lineItem, OrderFabric $orderFabric){

        $fabricTypeIsForValance = $orderFabric->type->isForValance();

        if($fabricTypeIsForValance){
            $cutLength = static::calculateValanceFabricCutLength($lineItem, $orderFabric);
        } else {
            $cutLength = static::calculateShadeFabricCutLength($lineItem, $orderFabric);
        }

        return $cutLength;
    }

    static private function calculateShadeFabricCutLength(LineItem $lineItem, OrderFabric $orderFabric){

        $cutLength = 0;

        //make sure product is set
        if(!$lineItem->product){
            return $cutLength;
        }

        //don't calculate for austrian at this time
        $isAustrian = $lineItem->product->shape === "Austrian";
        if($isAustrian){
            return $cutLength;
        }

        //don't calculate for embellishment at this time
        $isEmbellishment = ($orderFabric->type->type === 'embellishment');
        if($isEmbellishment){
            return $cutLength;
        }

        $cutLengthFormulaName = ($lineItem->product->cutLengthFormula->name)? $lineItem->product->cutLengthFormula->name : null;
        $cutLengthAdd = $lineItem->product->cut_length_add;
        $manufacturingLength = $lineItem->manufacturing_length;
        $length = $lineItem->height;
        $headerboard = ($lineItem->headerboard > 0)? $lineItem->headerboard: $lineItem->valance_headerboard;
        $panels = $lineItem->total_panels;
        $panelHeight = $lineItem->panel_height;
        $lengthPlus = $lineItem->product->length_plus;
        $lengthTimes = $lineItem->product->length_times;

        switch ($cutLengthFormulaName) {
            case "FH + Board":
                $cutLength = $manufacturingLength + $headerboard + $cutLengthAdd;
                break;
            case "L + Board":
                $cutLength = $length + $headerboard + $cutLengthAdd;
                break;
            case "L + Spaces x 2":
                $cutLength = $length + $panels * 2 + $cutLengthAdd;
                break;
            case "L + Spaces x 4":
                $cutLength = $length + $panels * 2 + $cutLengthAdd;
                break;
            case "L + Spaces x 6":
                $cutLength = $length + $panels * 2 + $cutLengthAdd;
                break;
            case "Spaces x PS":
                $cutLength = $panels * static::softFoldSpace($panelHeight) + $cutLengthAdd;
                break;
            case ($lengthPlus && $lengthTimes):
                $cutLength = $length * $lengthTimes + $lengthPlus;
                break;
            case ($lengthTimes):
                $cutLength = $length * $lengthTimes + $lengthPlus;
                break;
            case ($lengthPlus):
                $cutLength = $length + $lengthPlus + $cutLengthAdd;
                break;
        }

        return ceil($cutLength);
    }

    static private function calculateValanceFabricCutLength(LineItem $lineItem, OrderFabric $orderFabric){

        $headerboard = $lineItem->valance_headerboard;
        $length = $lineItem->valance_height;

        $cutLength = ($length + $headerboard) * 2 + 3;

        return ceil($cutLength);
    }

    static private function softFoldSpace($panelHeight){

        $softFoldSpace = $panelHeight;

        switch ($panelHeight) {
            case ($panelHeight >= 3 && $panelHeight <= 3.875):
                $softFoldSpace = 9;
                break;
            case ($panelHeight >= 4 && $panelHeight <= 5.375):
                $softFoldSpace = 11;
                break;
            case ($panelHeight >= 5.5 && $panelHeight <= 6.875):
                $softFoldSpace = 12;
                break;
            case ($panelHeight >= 7 && $panelHeight <= 7.875):
                $softFoldSpace = 13;
                break;
            case ($panelHeight >= 8 && $panelHeight <= 9):
                $softFoldSpace = 15;
                break;
        }

        return $softFoldSpace;
    }

    static private function fabricUnitPrice(LineItem $lineItem, $fabric, $companyPrice)
    {
        if(!$fabric instanceof \Fabric) {
            return 0;
        }

        if($companyPrice && isset($companyPrice->price) && !is_null($companyPrice->price)) {
            return round($companyPrice->price, 2);
        }

        $pricingOverride = $lineItem->fabric_override_price;
        if($pricingOverride){
            round($pricingOverride, 2);
        }

        return round($fabric->unit_price, 2);
    }

    static private function getFabricCompanyPrice(LineItem $lineItem, $fabric){

        $company = $lineItem->order->company;
        $hasCompany = $company instanceof \Company;

        if($hasCompany) {
            $companyPrice = $company->getCompanyFabricPrice($fabric->id);

            if($companyPrice) {
                return $companyPrice;
            }
        }

        return null;
    }

    static public function getOrderFabricLength(OrderFabric $orderFabric){

        $totalInches = 0;

        foreach($orderFabric->order->orderLines as $orderLine){

            $cuts = static::fabricCuts($orderLine, $orderFabric);
            $cutLength = static::fabricCutLength($orderLine, $orderFabric);

            $totalInches += $cuts * $cutLength;
        }

        //convert to yards
        $totalYards = round($totalInches / 36, 2);

        return $totalYards;
    }
}