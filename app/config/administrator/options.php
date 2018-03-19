<?php

return array(
    'title' => 'Options',
    'single' => 'Option',
    'model' => 'Option',
    'columns' => array(
        'id' => array(
            'title' => 'ID'
        ),
        'parent_id' => array(
            'title' => 'Parent',
            'relationship' => 'parents', //this is the name of the Eloquent relationship method!
            'select' => "name",
        ),
        'name' => array(
            'title' => 'Name'
        ),
        'pricing_type' => array(
            'title' => 'Pricing Type'
        ),
        'min_charge' => array(
            'title' => 'Min Charge'
        ),
        'pricing_value' => array(
            'title' => 'Pricing Value'
        )
    ),
    'edit_fields' => array(
        'parents' => array(
            'type' => 'relationship',
            'title' => 'Parent',
            'name_fields' => 'name'
        ),
        'pricing_type' => array(
            'type' => 'enum',
            'title' => 'Pricing Type',
            'options' => PricingType::getTypes()
        ),
        'min_charge' => array(
            'type' => 'number',
            'title' => 'Min Charge'
        ),
        'pricing_value' => array(
            'type' => 'text',
            'title' => 'Pricing Value'
        ),
        'price_as_fabric' => array(
            'type' => 'bool',
            'title' => 'Price as Fabric'
        ),
        'tier_formula' => array(
            'type' => 'text',
            'title' => 'Tier Formula'
        ),
        'assembler_note' => array(
            'type' => 'text',
            'title' => 'Assembler Note Text (optional)'
        ),
        'seamstress_note' => array(
            'type' => 'text',
            'title' => 'Seamstress Note Text (optional)'
        ),
        'embellisher_note' => array(
            'type' => 'text',
            'title' => 'Embellisher Note Text (optional)'
        ),
        'is_embellishment_option' => array(
            'type' => 'bool',
            'title' => 'Embellishment Option?',
        ),
        'is_interlining_option' => array(
            'type' => 'bool',
            'title' => 'Interlining Option?',
        ),
    ),
    'sort' => array(
        'field' => 'name',
        'direction' => 'asc',
    ),
    'filters' => array(
        'parents' => array(
            'title' => 'Parent',
            'type' => 'relationship',
            'name_field' => 'name'
        ),
        'name' => array(
            'title' => 'Name'
        ),
        'pricing_type' => array(
            'title' => 'Pricing Type',
            'type' => 'enum',
            'options' => PricingType::getTypes(),

        ),
    ),
);
