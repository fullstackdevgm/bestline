<?php

return array(
    'title' => 'Mounts',
    'single' => 'Mount',
    'model' => 'Lookups\Mount',
    'columns' => array(
        'id' => array(
            'title' => 'ID'
        ),
        'code' => array(
            'title' => 'Code',
        ),
        'description' => array(
            'title' => 'Description'
        )
    ),
    'edit_fields' => array(
        'code' => array(
            'title' => 'Code',
            'type' => 'text'
        ),
        'description' => array(
            'title' => 'Description',
            'type' => 'text'
        )
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