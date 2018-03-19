<?php

return array(
    'title' => 'Phone Numbers',
    'single' => 'Phone Number',
    'model' => 'PhoneNumber',
    'columns' => array(
        'id' => array(
            'title' => 'ID',
        ),
        'number' => array(
            'title' => 'Number'
        ),
        'type' => array(
            'title' => 'Type'
        ),
        'contacts' => array(
            'title' => 'Contact',
            'relationship' => 'contacts',
            'select' => "CONCAT((:table).first_name, ' ', (:table).last_name)",
        ),
    ),
    'edit_fields' => array(
        'number' => array(
            'type' => 'text',
            'title' => 'Phone Number'
        ),
        'type' => array(
            'type' => 'enum',
            'options' => PhoneNumber::getTypes(true)
        ),
        'contacts' => array(
            'title' => 'Contact',
            'type' => 'relationship',
            'name_field' => 'full_name',
        ),
    ),
    'sort' => array(
        'field' => 'number',
        'direction' => 'asc',
    ),
    'rules' => array(
        'number' => 'required',
        'type' => 'required',
    ),
    'filters' => array(
        'id' => array(
            'title' => 'ID'
        ),
        'number' => array(
            'type' => 'text',
            'title' => 'Phone Number'
        ),
        'type' => array(
            'title' => 'Type',
            'type' => 'enum',
            'options' => PhoneNumber::getTypes()
        ),
        'contacts' => array(
            'title' => 'Contact',
            'type' => 'relationship',
            'name_field' => 'full_name',
        ),
    ),
);
