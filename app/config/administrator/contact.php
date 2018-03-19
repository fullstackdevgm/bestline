<?php

return array(
    'title' => 'Contacts',
    'single' => 'Contact',
    'model' => 'Contact',
    'columns' => array(
        'id' => array(
            'title' => 'ID',
        ),
        'first_name' => array(
            'title' => 'First Name'
        ),
        'last_name' => array(
            'title' => 'Last Name'
        ),
        'title' => array(
            'title' => 'Title'
        ),
        'primaryEmail' => array(
            'title' => 'Primary Email',
            'relationship' => 'primaryEmail',
            'select' => "(:table).email",
        ),
    ),
    'edit_fields' => array(
        'first_name' => array(
            'type' => 'text',
            'title' => 'First Name'
        ),
        'last_name' => array(
            'type' => 'text',
            'title' => 'Last Name'
        ),
        'title' => array(
            'type' => 'text',
            'title' => 'Title'
        ),
        'company' => array(
            'type' => 'relationship',
            'select' => '(:table).name',
            'title' => 'Company'
        ),
        'primaryEmail' => array(
            'type' => 'relationship',
            'name_field' => 'email',
            'title' => 'Primary Email',
        ),
    ),
    'sort' => array(
        'field' => 'last_name',
        'direction' => 'asc',
    ),
    'rules' => array(
        'first_name' => 'required',
        'last_name' => 'required',
        'type' => 'required',
    ),
    'filters' => array(
        'id' => array(
            'title' => 'ID'
        ),
        'company' => array(
            'title' => 'Company',
            'type' => 'relationship'
        ),
        'first_name' => array(
            'type' => 'text',
            'title' => 'First Name'
        ),
        'last_name' => array(
            'type' => 'text',
            'title' => 'Last Name'
        ),
    ),
);
