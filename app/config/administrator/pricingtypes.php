<?php

return array(
    'title' => 'Pricing Types',
    'single' => 'Pricing Type',
    'model' => 'PricingType',
    'columns' => array(
        'id' => array(
            'title' => 'ID'
        ),
        'type' => array(
            'title' => 'Type'
        ),
        'formula' => array(
            'title' => 'Formula'
        )
    ),
    'edit_fields' => array(
        'type' => array(
            'type' => 'enum',
            'options' => PricingType::getTypes(),
            'title' => 'Type'
        ),
        'formula' => array(
            'type' => 'textarea',
            'title' => 'Formula'
        )
    ),
    'sort' => array(
        'field' => 'type',
        'direction' => 'asc',
    ),
    'filters' => array(
        'id' => array(
            'title' => 'ID'
        ),
    ),
);