<?php

namespace Order;

use Bestline\Event\Events;
use Bestline\Order\Calculator;
use Order\Finalized\LineItem as FinalizedLineItem;
use \Log;
use \Product;
use \Order\Fabric as OrderFabric;
use \Lookups\Hardware;
use \stdClass;
use \Station;

class LineItem extends \Eloquent
{
    protected $table = "order_lines";

    //optional properties not found in the database
    public $lengthMetricsCache;
    public $widthCache;
    public $optionsUnsaved = [];
    public $shade_override_price = null;
    public $fabric_override_price = null;
    public $options_override_price = null;
    public $company_id = null;
    public $station_id;
    public $pricing_length;
    public $pricing_width;

    static public function boot()
    {
        static::deleting(['Order\LineItem', 'onOrderLineDeleting']);
        static::created(['Order\LineItem', 'onOrderLineCreated']);
        static::updated(['Order\LineItem', 'onOrderLineUpdated']);
    }

    public static $heightAdjustmentOptions = array(
        1 => array(
            'name' => 'Height to Pouf',
            'product_key' => 'poufy_panels_to_pouf'
        ),
        2 => array(
            'name' => 'Height to Rod',
            'product_key' => 'poufy_panels_to_rod'
        ),
        3 => array(
            'name' => 'Drapery Bottom',
            'product_key' => null
        ),
    );

    //setup relationships
    public function order()
    {
        return $this->belongsTo('Order', 'order_id');
    }
    public function product()
    {
        return $this->belongsTo('Product', 'product_id');
    }
    public function options()
    {
        return $this->hasMany('Order\LineItem\Option', 'order_line_id');
    }
    public function pullType()
    {
        return $this->belongsTo('Lookups\PullType', 'pull_type_id');
    }
    public function cordPosition()
    {
        return $this->belongsTo('Lookups\CordPosition', 'cord_position_id');
    }
    public function hardware()
    {
        return $this->belongsTo('Lookups\Hardware', 'hardware_id');
    }
    public function mount()
    {
        return $this->belongsTo('Lookups\Mount', 'mount_id');
    }
    public function finalized(){
        return $this->hasOne('Order\Finalized\LineItem', 'order_line_id');
    }
    public function valanceType(){
        return $this->belongsTo('Lookups\ValanceType', 'valance_type_id');
    }

    public function currentWork(){

        $stationId = ($this->station_id)? $this->station_id : $this->current_station_id;
        return $this->hasOne('Order\LineItem\Work', 'order_line_id')->where('station_id', '=', $stationId);
    }

    public function currentStation(){
        return $this->hasOne('Station', 'id', 'current_station_id');
    }

    //add events
    static public function onOrderLineCreated(LineItem $lineItem)
    {
        \Event::fire(Events::ORDER_LINEITEM_CREATED, [\Auth::user(), $lineItem]);
    }

    static public function onOrderLineUpdated(LineItem $lineItem)
    {
        \Event::fire(Events::ORDER_LINEITEM_UPDATED, [\Auth::user(), $lineItem]);
    }

    static public function onOrderLineDeleting(LineItem $lineItem)
    {
        foreach($lineItem->options as $option) {
            $option->delete();
        }
    }

    //magic attribute functions
    public function getShadeCalcPriceAttribute() {
        return Calculator::calculateShadePrice($this);
    }

    public function getLineTotalPriceAttribute() {
        return Calculator::calculateOrderLine($this);
    }

    public function getPrettyShadeCalcPriceAttribute()
    {
        return '$' . number_format((float)$this->shade_calc_price, 2);
    }

    public function getPrettyTotalPriceAttribute()
    {
        return '$' . number_format((float)$this->total_price, 2);
    }

    //other functions
    public function finalize(){

        if($this->finalized instanceof FinalizedLineItem){
            $finalizedOrderLine = FinalizedLineItem::find($this->finalized->id);
        } else {
            $finalizedOrderLine = new FinalizedLineItem();
        }

        $finalizedOrderLine->shade_price = Calculator::calculateShadePrice($this);
        $finalizedOrderLine->valance_price = Calculator::calculateValancePrice($this);
        $finalizedOrderLine->fabric_price = Calculator::calculateFabricPrice($this);
        $finalizedOrderLine->options_price = Calculator::calculateOptionPrice($this);
        $finalizedOrderLine->total_price = $finalizedOrderLine->shade_price + $finalizedOrderLine->valance_price + $finalizedOrderLine->fabric_price + $finalizedOrderLine->options_price;

        $this->finalized()->save($finalizedOrderLine);

        foreach($this->options as $option) {
            $finalizedOption = $option->finalize();
        }

        $firstStationId = Station::all()->sortBy("sort_order")->first()->id;
        $this->current_station_id = $firstStationId;
        $this->save();

        return $this;
    }

    public static function isHeightAdjusted($heightAdjustmentOptionId){
        $heightAdjustmentOptionId = intval($heightAdjustmentOptionId);
        return (isset($heightAdjustmentOptionId) && ($heightAdjustmentOptionId === 1 || $heightAdjustmentOptionId === 2));
    }

    public static function getLineItemFromInputs($inputs){

        //get orderLine object
        if(isset($inputs['id'])){
            $lineItem = static::find($inputs['id']);

            if(!$lineItem instanceof static) {
                $lineItem = new static;
            }
        } else {
            $lineItem = new static;
        }

        $lineItem->mount_id = (isset($inputs['mount_id']) && $inputs['mount_id'] !== "")? $inputs['mount_id'] : null;
        $lineItem->order_id = (isset($inputs['order_id']) && $inputs['order_id'] !== "")? $inputs['order_id'] : null;
        $lineItem->product_id = (isset($inputs['product_id']) && $inputs['product_id'] !== "")? $inputs['product_id'] : null;

        $lineItem->has_shade = (isset($inputs['has_shade']) && $inputs['has_shade'] !== "")? $inputs['has_shade'] : true;
        $lineItem->width = (isset($inputs['width']) && $inputs['width'] !== "")? $inputs['width'] : null;
        $lineItem->height = (isset($inputs['height']) && $inputs['height'] !== "")? $inputs['height'] : null;
        $lineItem->headerboard = (isset($inputs['headerboard']) && $inputs['headerboard'] !== "")? $inputs['headerboard'] : 0;
        $lineItem->return = (isset($inputs['return']) && $inputs['return'] !== "")? $inputs['return'] : null;

        if($lineItem->has_shade){
            $lineItem->cord_length = (isset($inputs['cord_length']) && $inputs['cord_length'] !== "")? $inputs['cord_length'] : null;
            $lineItem->pull_type_id = (isset($inputs['pull_type_id']) && $inputs['pull_type_id'] !== "")? $inputs['pull_type_id'] : null;
            $lineItem->cord_position_id = (isset($inputs['cord_position_id']) && $inputs['cord_position_id'] !== "")? $inputs['cord_position_id'] : null;
            $lineItem->hardware_id = (isset($inputs['hardware_id']) && $inputs['hardware_id'] !== "")? $inputs['hardware_id'] : null;
            $lineItem->height_adjustment_option_id = (isset($inputs['height_adjustment_option_id']) && $inputs['height_adjustment_option_id'] !== "")? $inputs['height_adjustment_option_id'] : null;
        }

        $lineItem->has_valance = (isset($inputs['has_valance']) && $inputs['has_valance'] !== "")? $inputs['has_valance'] : false;

        if($lineItem->has_valance){
            $lineItem->valance_type_id = (isset($inputs['valance_type_id']) && $inputs['valance_type_id'] !== "")? $inputs['valance_type_id'] : null;
            $lineItem->valance_width = (isset($inputs['valance_width']) && $inputs['valance_width'] !== "")? $inputs['valance_width'] : 0;
            $lineItem->valance_height = (isset($inputs['valance_height']) && $inputs['valance_height'] !== "")? $inputs['valance_height'] : 0;
            $lineItem->valance_return = (isset($inputs['valance_return']) && $inputs['valance_return'] !== "")? $inputs['valance_return'] : 0;
            $lineItem->valance_headerboard = (isset($inputs['valance_headerboard']) && $inputs['valance_headerboard'] !== "")? $inputs['valance_headerboard'] : 0;
        }

        $lineItem->line_number = (isset($inputs['line_number']) && $inputs['line_number'] !== "")? $inputs['line_number'] : null;
        $lineItem->shade_price = (isset($inputs['shade_price']) && $inputs['shade_price'] !== "")? $inputs['shade_price'] : null;
        $lineItem->valance_price = (isset($inputs['valance_price']) && $inputs['valance_price'] !== "")? $inputs['valance_price'] : null;
        $lineItem->fabric_price = (isset($inputs['fabric_price']) && $inputs['fabric_price'] !== "")? $inputs['fabric_price'] : null;
        $lineItem->options_price = (isset($inputs['options_price']) && $inputs['options_price'] !== "")? $inputs['options_price'] : null;
        $lineItem->total_price = (isset($inputs['total_price']) && $inputs['total_price'] !== "")? $inputs['total_price'] : null;

        $lineItem->shade_override_price = (isset($inputs['shade_override_price']) && $inputs['shade_override_price'] !== "")? $inputs['shade_override_price'] : null;
        $lineItem->fabric_override_price = (isset($inputs['fabric_override_price']) && $inputs['fabric_override_price'] !== "")? $inputs['fabric_override_price'] : null;
        $lineItem->options_override_price = (isset($inputs['options_override_price']) && $inputs['options_override_price'] !== "")? $inputs['options_override_price'] : null;

        return $lineItem;
    }

    //temporarily moved from Order\Finalized\LineItem
    private function calculateLengthMetrics(){

        return Calculator::calculateLengthMetrics($this);
    }

    public function getPanelHeightAttribute()
    {
        return $this->calculateLengthMetrics()->panel_height;
    }

    public function getTotalPanelsAttribute()
    {
        return $this->calculateLengthMetrics()->total_panels;
    }

    public function getSkirtHeightAttribute()
    {
        return $this->calculateLengthMetrics()->skirt_height;
    }

    public function getManufacturingLengthAttribute()
    {
        return $this->calculateLengthMetrics()->manufacturing_length;
    }

    private function calculateWidthMetrics(){

        return Calculator::calculateWidthMetrics($this);
    }

    public function getRingSpacingAttribute()
    {
        return $this->calculateWidthMetrics()->ring_spacing;
    }

    public function getTotalRingColumnsAttribute()
    {
        return $this->calculateWidthMetrics()->total_ring_columns;
    }

    public function getManufacturingWidthAttribute()
    {
        return $this->calculateWidthMetrics()->manufacturing_width;
    }

    public function getTdbuDeductionAttribute(){

        $tdbuDeduction = 0;

        //set deduction if product is tdbu/bu
        $hasTdbuOption = $this->checkForOption('Top Down Bottom Up');
        if($hasTdbuOption){

            if($this->hardware && $this->hardware->code){

                switch ($this->hardware->code) {
                    case 'standard':
                        $tdbuDeduction = $this->product->cord_lock_deduction;
                        break;
                    case 'cord_lock':
                        $tdbuDeduction = $this->product->cord_lock_deduction;
                        break;
                    case 'continuous_cord':
                        $tdbuDeduction = $this->product->clutch_deduction;
                        break;
                    case 'motorization':
                        $tdbuDeduction = $this->product->motorized_deduction;
                        break;
                }
            }
        }

        return $tdbuDeduction;
    }

    public function getNumExtraPanelsAttribute(){

        $numExtraPanels = 0;

        if($this->product && $this->product->is_poufy){
            if($this->height_adjustment_option_id){

                $isHeightAdjusted = static::isHeightAdjusted($this->height_adjustment_option_id);

                if($isHeightAdjusted){

                    $heightAdjustmentOption = static::$heightAdjustmentOptions[$this->height_adjustment_option_id];
                    $numExtraPanels = $this->product->{$heightAdjustmentOption['product_key']};
                }
            }
        }

        return $numExtraPanels;
    }

    public function getAssemblerNotesAttribute(){

        $assemblerNotes = []; //add with $assemblerNotes[] = ;

        $hasReturn = $this->return > 0;
        $isPoufy = $this->product->is_poufy;
        $hasEmbellishmentBottom = ($this->embellishment_option && $this->embellishment_option->data && $this->embellishment_option->data->size_bottom);

        if($isPoufy) {
            if($hasReturn) {
                $assemblerNotes[] = "Has Return";
            } else {
                $assemblerNotes[] = "No Return";
            }
        }

        $isHeightAdjusted = static::isHeightAdjusted($this->height_adjustment_option_id);
        if($isHeightAdjusted) {
            $assemblerNotes[] = 'Tie up: ' . $this->height;
        }

        if($this->height_adjustment_option_id){
            $assemblerNotes[] = static::$heightAdjustmentOptions[$this->height_adjustment_option_id]['name'];
        }

        if($isPoufy){
            if($hasEmbellishmentBottom){
                $assemblerNotes[] = "Rod Outside";
            } else {
                $assemblerNotes[] = "Rod Inside";
            }
        }

        foreach($this->options as $option){
            if($option->option->assembler_note) {
                $assemblerNotes[] = $option->option->assembler_note;
            }
            if($option->sub_option->assembler_note) {
                $assemblerNotes[] = $option->sub_option->assembler_note;
            }
            if($option->data && $option->data->assembler_note){
                $assemblerNotes[] = $option->data->assembler_note;
            }
        }

        return $assemblerNotes;
    }

    public function getHeaderboardDimensionsAttribute()
    {
        $headerboards = [];
        $anEightOfAnInch = 0.125;

        if($this->headerboard > 0){

            //find board width
            $boardWidth = $this->width - $anEightOfAnInch;
            $headerboards[] = "1\" x {$this->headerboard}\" x {$boardWidth}\"";
        }

        if($this->valance_headerboard > 0){
            $valanceBoardWidth = $this->valance_width - $anEightOfAnInch;
            $headerboards[] = "1\" x {$this->valance_headerboard}\" x {$valanceBoardWidth}\"";
        }

        return $headerboards;
    }

    public function getRodDimensions(){

        $dimensions = [];
        $orderLineWidth = $this->width;
        $totalPanels = $this->total_panels;

        //every shade has a metal rod at the bottom
        $metalRod = Product::getRodType('metal');
        $metalRodWidth = $orderLineWidth - $metalRod['deduction'];
        $dimensions[] = "1 x {$metalRod['name']} @ {$metalRodWidth}\"";

        //add rod if order line has valance
        if($this->has_valance){
            $valanceRodWidth = $this->valance_width - $metalRod['deduction'];
            $dimensions[] = "1 x {$metalRod['name']} @ {$valanceRodWidth}\"";
        }

        //any product with a rod type gets panel rods
        if($this->product->rod_type){
            $productRod = Product::getRodType($this->product->rod_type);
            $productRodWidth = $orderLineWidth - $productRod['deduction'];
            $dimensions[] = "{$totalPanels} x {$productRod['name']} @ {$productRodWidth}\"";
        }

        //products without a rod type only get rods if they have a rod option
        if(!$this->product->rod_type){
            $rodOption = $this->getRodOption();
            if($rodOption){
                if($rodOption->sub_option->name === "Plastic Rods Inserted Into Shade"){
                    $plasticRod = Product::getRodType('flat_plastic');
                    $plasticRodWidth = $orderLineWidth - $plasticRod['deduction'];
                    $dimensions[] = "{$totalPanels} x {$plasticRod['name']} @ {$plasticRodWidth}\"";
                }
            }
        }

        return $dimensions;
    }

    private function getRodOption(){

        $rodOption = null;

        foreach($this->options as $option){
            if($option->option->name === 'Rod'){
                $rodOption = $option;
            }
        }

        return $rodOption;
    }

    public function getHeaderboardCoverFabricAttribute(){

        //find fabrics
        foreach($this->order->fabrics as $fabric){
            if($fabric->type->type === 'face'){
                $orderFaceFabric = $fabric;
            }
            if($fabric->type->type === 'lining'){
                $orderLiningFabric = $fabric;
            }
        }

        //if order has no lining = face
        if(!isset($orderLiningFabric)){
            return $orderFaceFabric;
        }

        //find orderline data
        $mounts = [];
        $valance = [];
        $sideTabs = [];
        foreach($this->order->orderLines as $orderLine){

            //find mounts
            $mounts[] = $orderLine->mount->code;

            //find valances
            if($orderLine->has_valance){
                $valance[] = 1;
            } else {
                $valance[] = 0;
            }

            //find side tabs
            $orderLineHasSideTabs = 0;
            foreach($orderLine->options as $option){
                if($option->option->name === 'Side Tabs'){
                    $orderLineHasSideTabs = 1;
                }
            }
            $sideTabs[] = $orderLineHasSideTabs;
        }

        //setup logic for
        $hasInsideMount = in_array('IB', $mounts);
        $hasOutsideMount = in_array('OB', $mounts);
        $hasValance = in_array(1, $valance);
        $hasSideTabs = in_array(1, $sideTabs);
        $hasReturn = $this->return > 0;
        $isPoufy = $this->product->is_poufy;

        //if has return and is poufy
        if($hasReturn && $isPoufy) {
            return $orderLiningFabric;
        }

        //if all inside mount = lining
        if($hasInsideMount && !$hasOutsideMount){
            return $orderLiningFabric;
        }

        //all outside,      all side tabs                   = lining
        //mixed mount,      all outside side tabs,          = lining
        if($hasSideTabs){
            return $orderLiningFabric;
        }

        //all outside,      no side tabs,   all valance     = lining
        if($hasValance){
            return $orderLiningFabric;
        }

        //all outside,      no side tabs                    = face
        //mixed mount,      no side tabs,                   = face
        return $orderFaceFabric;
    }

    public function getHeaderboardCoverCutLengthAttribute(){

        $headerboardCoverCutLength = 0;

        $cuts = Calculator::fabricCuts($this, $this->headerboard_cover_fabric);

        $headerboard = floatval($this->headerboard) * 3 * $cuts;
        $valance = floatval($this->valance_headerboard) * 3 * $cuts;

        $headerboardCoverCutLength = $headerboard + $valance;

        return $headerboardCoverCutLength;
    }

    public function getManufacturingHeaderboardAttribute(){

        $manufacturingHeaderboard = $this->headerboard;

        if(!($manufacturingHeaderboard > 0)){
            return 0;
        }

        $isOutsideMount = $this->mount->code === 'OB';
        if($isOutsideMount){
            $manufacturingHeaderboard = $manufacturingHeaderboard + 0.75;
        }

        return $manufacturingHeaderboard;
    }

    public function getManufacturingValanceHeaderboardAttribute(){

        $manufacturingValanceHeaderboard = $this->valance_headerboard;

        if(!($manufacturingValanceHeaderboard > 0)){
            return 0;
        }

        $isOutsideMount = $this->mount->code === 'OB';
        if($isOutsideMount){
            $manufacturingValanceHeaderboard = $manufacturingValanceHeaderboard + 0.75;
        }

        return $manufacturingValanceHeaderboard;
    }

    public function getHeightAdjustmentOptions(){
        return static::$heightAdjustmentOptions;
    }

    public static function getHeightAdjustmentIdByName($name){

        $heightAdjustmentId = null;
        foreach(static::$heightAdjustmentOptions as $id => $heightAdjustmentOption){
            if($heightAdjustmentOption['name'] === $name){
                $heightAdjustmentId = $id;
            }

        }
        return $heightAdjustmentId;
    }

    public function getSidetabWidthAttribute(){
        return $this->headerboard * 2 + 1;
    }

    public function getSidetabHeightAttribute(){
        return 6;
    }

    public function getEmbellishmentOptionAttribute(){

        $orderLine = $this;
        $embellishmentOption = null;

        foreach($orderLine->options as $option){

            if($option->order_fabric && $option->order_fabric->type->type === 'embellishment'){

                $option->order_fabric->fabric->id; //load the order fabric

                $embellishmentOption = $option;
                break;
            }
        }

        return $embellishmentOption;
    }

    public function getEmbellisherNotesAttribute(){

        $embellisherNotes = [];

        foreach($this->options as $option){
            if($option->option->embellisher_note) {
                $embellisherNotes[] = $option->option->embellisher_note;
            }
            if($option->sub_option->embellisher_note) {
                $embellisherNotes[] = $option->sub_option->embellisher_note;
            }
        }

        return $embellisherNotes;
    }

    public function getSeamstressNotesAttribute(){

        $seamstressNotes = [];

        foreach($this->options as $option){
            if($option->option->seamstress_note) {
                $seamstressNotes[] = $option->option->seamstress_note;
            }
            if($option->sub_option->seamstress_note) {
                $seamstressNotes[] = $option->sub_option->seamstress_note;
            }
        }

        return $seamstressNotes;
    }

    public function subOptionReadableList()
    {
        return $this->options->implode('suboption_name', ', ');
    }

    public function getFabricCuts(OrderFabric $lineFabric){

        $cuts = Calculator::fabricCuts($this, $lineFabric);
        return $cuts;
    }

    public function getFabricCutLength(OrderFabric $lineFabric){

        $cutLength = Calculator::fabricCutLength($this, $lineFabric);
        return $cutLength;
    }

    public function checkForSubOption($subOptionName){
        $foundSubOption = false;

        foreach($this->options as $orderLineOption){
            if($orderLineOption->subOption->name === $subOptionName){
                $foundSubOption = true;
            }
        }

        return $foundSubOption;
    }

    public function checkForOption($optionName){
        $foundOption = false;

        foreach($this->options as $orderLineOption){
            if($orderLineOption->option->name === $optionName){
                $foundOption = true;
            }
        }

        return $foundOption;
    }
}