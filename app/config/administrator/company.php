<?php

return array(
    'title' => 'Companies',
    'single' => 'Company',
    'model' => 'Company',
    'columns' => array(
        'id' => array(
            'title' => 'ID',
        ),
        'name' => array(
            'title' => 'Name'
        ),
        'website' => array(
            'title' => 'Web Site'
        ),
        'notes' => array(
            'title' => 'Notes'
        ),
        'account_no' => array(
            'title' => 'Account Num'
        ),
        'credit_terms' => array(
            'title' => 'Credit Terms'
        ),
        'credit_term_notes' => array(
            'title' => 'Credit Notes'
        ),
        'customerType' => array(
            'title' => 'Customer Type',
            'relationship' => 'customerType',
            'select' => '(:table).name'
        )
    ),
    'edit_fields' => array(
        'name' => array(
            'title' => 'Company Name',
            'type' => 'text'
        ),
        'account_no' => array(
            'title' => 'Account Num',
            'type' => 'text'
        ),
        'customerType' => array(
            'title' => 'Customer Type',
            'type' => 'relationship',
            'select' => '(:table).name'
        ),
        'website' => array(
            'title' => 'Website',
            'type' => 'text'
        ),
        'credit_terms' => array(
            'title' => 'Credit Terms',
            'type' => 'enum',
            'options' => Company::getCreditTerms()
        ),
        'credit_term_notes' => array(
            'title' => 'Credit Notes',
            'type' => 'textarea'
        ),
        'notes' => array(
            'title' => 'Notes',
            'type' => 'textarea'
        ),
        'contacts' => array(
            'title' => 'Contacts',
            'type' => 'relationship',
            'name_field' => 'full_name',
            'auto_complete' => true,
            'num_options' => '10',
        )
    ),
    'sort' => array(
        'field' => 'name',
        'direction' => 'asc',
    ),
    'rules' => array(
        'name' => 'required',
        'customer_type_id' => 'required',
        'credit_terms' => 'required',
    ),
    'filters' => array(
        'id' => array(
            'title' => 'ID'
        ),
        'name' => array(
            'title' => 'Name'
        ),
        'customerType' => array(
            'title' => 'Customer Type',
            'type' => 'relationship',
            'name_field' => 'name'
        ),
    ),
);
