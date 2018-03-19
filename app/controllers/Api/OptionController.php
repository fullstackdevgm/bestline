<?php

namespace Api;

use Bestline\Order\Calculator;
use Order\LineItem;

class OptionController extends \Api\BaseController
{    
    public function getName($oid, $soid = null)
    {
        $retval = "";
        
        $option = \Option::where('id', '=', $oid)->first();
        
        if((!$option instanceof \Option)) {
            return \Response::json("Invalid Option", 500);
        }
        
        $retval = $option->name;
        
        if(!is_null($soid)) {
            $suboption = \Option::where('id', '=', $soid)->first();
            
            if(!$suboption instanceof \Option) {
                return \Response::json('Invalid Option', 500);
            }
            
            $retval .= " - {$suboption->name}";
        }
        
        return \Response::json($retval);
    }
    
    public function getIndex($optionId = null)
    {
        switch(true) {
            case is_null($optionId):
                $retval = \Option::findBaseOptions()->get()->toArray();
                break;
            default:
                $retval = [];
                
                $parent = \Option::where('id', '=', $optionId)->first();
                
                if(!$parent instanceof \Option) {
                    return \Response::json("Invalid Option", 500);
                }
                
                $children = \Option::find($optionId)->getImmediateDescendants();
                $children->load('data');
                
                $retval= $parent->toArray();
                $retval['sub_options'] = $children->toArray();
                break;
        }
        
        
        return \Response::json($retval);
    }
    
    public function getList()
    {
        $retval = \Option::findBaseOptions()->lists('name', 'id');
        return \Response::json($retval);
    }

    public function getAll()
    {
        $retval = \Option::findBaseOptions()->get();
        return \Response::json($retval);
    }
    
    public function getTree()
    {
        $options = \Option::all();
        
        $retval = [];
        
        foreach($options as $option) {
            $node = [
                'id' => $option->id,
                'text' => $option->name,
                'icon' => 'fa fa-fw fa-file-text-o',
                'state' => [
                    'opened' => false,
                    'disabled' => false,
                    'selected' => false
                ],
                'li_attr' => [],
                'a_attr' => []
            ];
            
            if(is_null($option->parent_id)) {
                $node['parent'] = '-1';
                $node['type'] = "option";
            } else {
                $node['parent'] = $option->parent_id;
                $node['type'] = "suboption";
            }
            
            $retval[] = $node;
        }
        
        $retval[] = [
            'id' => -1,
            'type' => 'root',
            'text' => 'Bestline Options',
            'icon' => 'fa fa-fw fa-file-text-o',
            'state' => [
                'opened' => false,
                'disabled' => false,
                'selected' => false
            ],
            'li_attr' => [],
            'a_attr' => [],
            'parent' => '#'
        ];
        
        return \Response::json($retval);
    }
    
    public function postDelete($id)
    {
        $option = \Option::find($id);
        
        if(!$option instanceof \Option) {
            return \Response::json("Failed to locate Option", 500);
        }
        
        $option->delete();
        
        return \Response::json('ok');
    }
    
    public function postSave()
    {
        $input = \Input::all();
        
        $validator = \Validator::make($input,
            [
                'id' => 'integer|min:1',
                'parent_id' => 'integer|min:1|exists:options,id',
                'option_name' => 'min:1|max:255',
                'option_pricing_type' => 'exists:pricing_types,type',
                'option_min_charge' => ['regex:/^\d*(\.\d{2})?$/'],
                'option_pricing_value' => ['regex:/^\d*(\.\d{2,3})?$/']
            ]
        );
        
        if($validator->fails()) {
            return \Response::json($validator->errors());
        }
        
        $option = null;
        
        if(isset($input['id'])) {
            $option = \Option::find($input['id']);
        }
        
        if(!$option instanceof \Option) {
            $option = new \Option();
        }
        
        if(isset($input['option_name'])) {
            $option->name = $input['option_name'];
        }
        
        if(isset($input['option_pricing_value'])) {
            $option->pricing_type = $input['option_pricing_value'];
        }
        
        if(isset($input['option_min_charge'])) {
            $option->min_charge = $input['option_min_charge'];
        }
        
        if(isset($input['option_pricing_value'])) {
            $option->pricing_value = $input['option_pricing_value'];
        }
        
        if(isset($input['parent_id'])) {
            $parent = \Option::find($input['parent_id']);
            
            if(!$parent instanceof \Option) {
                return \Response::json('Could not locate Parent', 500);
            }
        } 
        
        $option->save();
        
        if($parent instanceof \Option) {
            $option->makeChildOf($parent);
        }
        
        return \Response::json($option);
    }
}