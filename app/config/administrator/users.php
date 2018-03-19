<?php

return array(
     /**
      * Model title
      *
      * @type string
      */
     'title' => 'Users',

     /**
      * The singular name of your model
      *
      * @type string
      */
     'single' => 'user',

     /**
      * The class name of the Eloquent model that this config represents
      *
      * @type string
      */
     'model' => 'User',

     /**
      * The columns array
      *
      * @type array
      */
     'columns' => array(
         'id' => array(
             'title' => 'ID'
         ),
         'username' => array(
             'title' => 'User Name'

         ),
         'first_name' => array(
            'title' => 'First Name'
          ),
         'last_name' => array(
             'title' => 'Last Name'

         ),
         'email' => array(
             'title' => 'Email'
         ),        
        'station' => array(
            'title' => 'Station',
            'relationship' => 'station',
            'select' => "(:table).name",
        ),
        'password' => array(
            'title' => 'Password',
            'visible' => false
        )
     ),

     'edit_fields' => array(
         'username' => array(
             'title' => 'User Name',
             'type' => 'text'
         ),
         'first_name' => array(
             'title' => 'First Name',
             'type' => 'text'
         ),
         'last_name' => array(
             'title' => 'Last Name',
             'type' => 'text'
         ),
         'email' => array(
             'title' => 'Email',
             'type' => 'text'
         ),         
         'station' => array(
             'title' => 'Station',
             'type' => 'relationship',
             'name_field' => 'name',
         ),
         'password' => array(
             'title' => 'Password',
             'type' => 'password',
         ),
     ),

     'sort' => array(
         'field' => 'username',
         'direction' => 'asc',
     ),

    /**
     * The filter fields
     *
     * @type array
     */
    'filters' => array(
        'id' => array(
           'title' => 'ID'
        ),
        'username' => array(
            'title' => 'User Name',
        ),
        'first_name' => array(
           'title' => 'First Name'

         ),
        'last_name' => array(
            'title' => 'Last Name'

        ),
        'email' => array(
            'title' => 'Email'

        ),
        'station' => array(
            'title' => 'Station',
            'type' => 'relationship',
            'name_field' => 'name',
        ),
    ),
 );