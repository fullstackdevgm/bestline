<?php

return array(
    'title' => 'Shipping Methods',
    'single' => 'Shipping Method',
    'model' => 'ShippingMethod',
    'columns' => array(
        'id' => array(
            'title' => 'ID'
        ),
        'name' => array(
            'title' => 'Name'
        ),
        'description' => array(
            'title' => 'Description'
        ),
        'formula' => array(
            'title' => 'Formula'
        )
    ),
    'edit_fields' => array(
        'name' => array(
            'type' => 'text',
            'title' => 'Name'
        ),
        'description' => array(
            'type' => 'textarea',
            'title' => 'Description'
        ),
        'formula' => array(
            'type' => 'textarea',
            'title' => 'Formula'
        )
    ),
    'sort' => array(
        'field' => 'name',
        'direction' => 'asc',
    ),
    'filters' => array(
        'id' => array(
            'title' => 'ID'
        ),
    ),
);