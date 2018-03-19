<?php

namespace Inventory;

use Illuminate\Routing\Controller;
use \Inventory\Fabric as FabricInventory;
use \Input;
use \Inventory\Part as PartInventory;
use Aws\S3\S3Client;
use \Log;
use \Fabric\Type as FabricType;
use \Option;
use \PricingType;
use Carbon\Carbon;

class InventoryController extends Controller
{
    public function editFabric($id = null)
    {
        $fabric = \Fabric::find($id);

        if(!$fabric instanceof \Fabric) {
            $fabric = new \Fabric();
        }

        $companies = \Company::all()->lists('name', 'id');
        $fabricTypes = FabricType::all()->lists('name', 'id');
        $isCOM = ((bool)\Input::get('com', false) || $fabric->owner_company_id);
        $options = Option::whereNull('parent_id')->lists('name', 'id');
        $pricingTypes = PricingType::getTypes();

        return \View::make('inventory.fabric.edit', compact('fabric', 'companies', 'isCOM','fabricTypes', 'options', 'pricingTypes'));
    }

    public function deleteFabric($id) {
        $fabric = \Fabric::find($id);

        if(!$fabric instanceof \Fabric) {
            return \Redirect::route('inventory.dashboard')->with('error', 'Invalid ID');
        }

        $fabric->delete();

        return \Redirect::route('inventory.dashboard')->with('success', 'Fabric Deleted!');
    }

    public function adjustPart($id)
    {
        $part = \Part::find($id);

        if(!$part instanceof \Part) {
            return \Redirect::route('inventory.dashboard')->with('error', "Invalid ID");
        }

        $validator = \Validator::make(\Input::all(), [
            'adjustment' => 'required|integer',
            'reason' => 'required'
        ]);

        if($validator->fails()) {
            return \Redirect::route('inventory.dashboard')->withErrors($validator);
        }

        $quantity = $part->quantity;

        $newQuantity = $quantity + Input::get('adjustment');

        if($newQuantity < 0) {
            return \Redirect::route('inventory.part.edit', [$part->id])->with('error', 'Cannot have a negative available quantity');
        }

        $adjustment = new PartInventory();
        $adjustment->part_id = $part->id;
        $adjustment->adjustment = (int)Input::get('adjustment');
        $adjustment->quantity = $newQuantity;
        $adjustment->by_user_id = \Auth::user()->id;
        $adjustment->reason = Input::get('reason');

        $adjustment->save();

        return \Redirect::route('inventory.part.edit', [$part->id])->with('success', "Inventory Updated");
    }

    public function deletePart($id) {
        $part = \Part::find($id);

        if(!$part instanceof \Part) {
            return \Redirect::route('inventory.dashboard')->with('error', 'Invalid ID');
        }

        $part->delete();

        return \Redirect::route('inventory.dashboard')->with('success', 'Part Deleted');
    }

    public function addPart()
    {
        $part = new \Part();
        return \View::make('inventory.part.edit', compact('part'));
    }

    public function editPart($id)
    {
        $part = \Part::find($id);
        return \View::make('inventory.part.edit', compact('part'));
    }

    public function savePart($id = null)
    {
        if(!is_null($id)) {
            $part = \Part::find($id);
        } else {
            $part = new \Part();
        }

        if(!$part instanceof \Part) {
            throw new \Exception("Invalid Part");
        }

        $validator = \Validator::make(\Input::all(), [
            'name' => 'required|min:3',
            'description' => 'required|min:10',
            'minimum_qty' => 'required|integer|min:0',
            'quantity' => 'sometimes|integer|min:0'
        ]);

        if($validator->fails()) {
            if(is_null($id)) {
                return \Redirect::route('inventory.part.add')->withErrors($validator)->withInput();
            } else {
                return \Redirect::route('inventory.part.edit', $id)->withErrors($validator)->withInput();
            }
        }

        $part->name = \Input::get('name');
        $part->description = \Input::get('description');
        $part->minimum_qty = \Input::get('minimum_qty');
        $part->save();

        if(is_null($id)) {
            $quantity = \Input::get('quantity');

            if($quantity <= 0) {
                return \Redirect::route('inventory.part.add')->with('error', 'You must provide an initial quantity');
            }

            $adjustment = new PartInventory();
            $adjustment->part_id = $part->id;
            $adjustment->adjustment = $quantity;
            $adjustment->quantity = $quantity;
            $adjustment->by_user_id = \Auth::user()->id;
            $adjustment->reason = "Initial Addition";
            $adjustment->save();

            return \Redirect::route('inventory.dashboard')->with('success', "Part Added Succesfully");
        }

        return \Redirect::route('inventory.part.edit', $part->id)->with('success', "Part Updated Succesfully");
    }
}