<?php

return array(
    'title' => 'Hardware',
    'single' => 'Hardware',
    'model' => 'Lookups\Hardware',
    'columns' => array(
        'id' => array(
            'title' => 'ID'
        ),
        'code' => array(
            'title' => 'Code',
        ),
        'description' => array(
            'title' => 'Description'
        ),
        'formula' => array(
            'title' => 'Formula'
        ),
        'relatedOption' => array(
            'title' => 'Related Option',
            'relationship' => 'relatedOption',
            'select' => '(:table).name'
        ),
    ),
    'edit_fields' => array(
        'code' => array(
            'title' => 'Code',
            'type' => 'text'
        ),
        'description' => array(
            'title' => 'Description',
            'type' => 'text'
        ),
        'formula' => array(
            'title' => 'Formula',
            'type' => 'text'
        ),
        'relatedOption' => array(
            'title' => 'Related Option',
            'type' => 'relationship',
            'name_field' => 'name',
            'auto_complete' => true,
            'options_filter' => function($query){
                $query->whereNull('parent_id');
            }
        ),
    ),
    'sort' => array(
        'field' => 'code',
        'direction' => 'asc',
    ),
    'filters' => array(
        'id' => array(
            'title' => 'ID'
        ),
    ),
);