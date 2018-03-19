<?php

return array(
    'title' => 'Addresses',
    'single' => 'address',
    'model' => 'Address',
    'columns' => array(
        'id' => array(
            'title' => 'ID',
        ),
        'address1' => array(
            'title' => 'Address Line 1'
        ),
        'address2' => array(
            'title' => 'Address Line 2'
        ),
        'city' => array(
            'title' => 'City'
        ),
        'state' => array(
            'title' => 'State'
        ),
        'zip' => array(
            'title' => 'Zip Code'
        ),
        'type' => array(
            'title' => 'Address Type'
        ),
        'companies' => array(
            'title' => 'Company',
            'relationship' => 'companies',
            'select' => "(:table).name",
        ),
    ),
    'edit_fields' => array(
        'address1' => array(
            'title' => 'Address Line 1',
            'type' => 'text'
        ),
        'address2' => array(
            'title' => 'Address Line 2',
            'type' => 'text'
        ),
        'city' => array(
            'title' => 'City',
            'type' => 'text'
        ),
        'state' => array(
            'title' => 'State',
            'type' => 'enum',
            'options' => State::getStatesList()
        ),
        'zip' => array(
            'title' => 'Zip Code',
            'type' => 'text'
        ),
        'type' => array(
            'title' => 'Address Type',
            'type' => 'enum',
            'options' => Address::getTypes(true)
        ),
        'companies' => array(
            'title' => 'Company',
            'type' => 'relationship',
            'name_field' => 'name',
        ),
    ),
    'sort' => array(
        'field' => 'address1',
        'direction' => 'asc',
    ),
    'rules' => array(
        'address1' => 'required',
        'city' => 'required',
        'state' => 'required',
        'zip' => 'required',
        'type' => 'required',
    ),
    'filters' => array(
        'id' => array(
            'title' => 'ID'
        ),
        'address1' => array(
            'title' => 'Address Line 1'
        ),
        'companies' => array(
            'title' => 'Company',
            'type' => 'relationship'
        ),
    ),
);
