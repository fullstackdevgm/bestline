<?php

return array(
    'title' => 'Fabrics',
    'single' => 'Fabric',
    'model' => 'Fabric',
    'columns' => array(
        'id' => array(
            'title' => 'ID'
        ),
        'pattern' => array(
            'title' => 'Pattern'
        ),
        'color' => array(
            'title' => 'Color'
        ),
        'width' => array(
            'title' => 'Width'
        ),
        'repeat' => array(
            'title' => 'Repeat'
        ),
        'unit_price' => array(
            'title' => 'Unit Price'
        ),
        'relatedOption' => array(
            'title' => 'Related Option',
            'relationship' => 'relatedOption',
            'select' => '(:table).name'
        ),
    ),
    'edit_fields' => array(
        'pattern' => array(
            'type' => 'text',
            'title' => 'Fabric Pattern',
        ),
        'color' => [
            'type' => 'text',
            'title' => 'Fabric Color'
        ],
        'width' => array(
            'type' => 'text',
            'title' => 'Fabric Width'
        ),
        'repeat' => array(
            'type' => 'text',
            'title' => 'Repeat'
        ),
        'unit_price' => array(
            'type' => 'text',
            'title' => 'Unit Price'
        ),
        'types' => [
            'type' => 'relationship',
            'title' => 'Fabric Type',
            'name_field' => 'name',
            
        ],
        'relatedOption' => [
            'type' => 'relationship',
            'title' => 'Related Option',
            'name_field' => 'name',
            
        ]
    ),
    'sort' => array(
        'field' => 'pattern',
        'direction' => 'asc',
    ),
    'filters' => array(
        'id' => array(
            'title' => 'ID'
        ),
        'pattern' => array(
            'type' => 'text',
            'title' => 'Fabric Pattern',
        ),
        'color' => [
            'type' => 'text',
            'title' => 'Fabric Color'
        ],
        'types' => [
            'type' => 'relationship',
            'title' => 'Fabric Type',
            'name_field' => 'name',
        ],
        'relatedOption' => [
            'type' => 'relationship',
            'title' => 'Related Option',
            'name_field' => 'name',
            
        ]
    ),
);