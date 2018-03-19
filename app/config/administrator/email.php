<?php

return array(
    'title' => 'Emails',
    'single' => 'Email',
    'model' => 'Email',
    'columns' => array(
        'id' => array(
            'title' => 'ID',
        ),
        'email' => array(
            'title' => 'Email'
        ),
        'contact' => array(
            'title' => 'Contact',
            'relationship' => 'contact',
            'select' => "CONCAT((:table).first_name, ' ', (:table).last_name)",
        ),
    ),
    'edit_fields' => array(
        'email' => array(
            'type' => 'text',
            'title' => 'Email'
        ),
        'primary' => array(
            'type' => 'bool',
        ),
        'contact' => array(
            'title' => 'Contact',
            'type' => 'relationship',
            'name_field' => 'full_name',
        ),
    ),
    'sort' => array(
        'field' => 'email',
        'direction' => 'asc',
    ),
    'rules' => array(
        'email' => 'required',
    ),
    'filters' => array(
        'id' => array(
            'title' => 'ID'
        ),
        'email' => array(
            'title' => 'email',
            'type' => 'text',
        ),
        'contact' => array(
            'title' => 'Contact',
            'type' => 'relationship',
            'name_field' => 'full_name',
        ),
    ),
);
