<?php
/**
 * Created by PhpStorm.
 * User: JreamOQ ( jreamoq@gmail.com )
 * Date: 12/9/20
 * Time: 10:10
 */
return [
    'new-form-fields' => [
        [
            'id' => 'first_name',
            'label' => ilangs('First Name'),
            'type' => 'text',
            'std' => '',
            'layout' => 'col-md-6 col-12',
            'validation' => 'required',
        ],
        [
            'id' => 'last_name',
            'label' => ilangs('Last Name'),
            'type' => 'text',
            'std' => '',
            'layout' => 'col-md-6 col-12',
        ],
        [
            'id' => 'email',
            'label' => ilangs('Email'),
            'type' => 'text',
            'std' => '',
            'layout' => 'col-12',
            'validation' => 'required',
            'break' => true,
        ],
        [
            'id' => 'role',
            'label' => ilangs('Role'),
            'type' => 'select',
            'std' => 3,
            'layout' => 'col-12',
            'choices' => get_user_roles(true)
        ],
        [
            'id' => 'password',
            'label' => ilangs('Password'),
            'type' => 'text',
            'std' => '',
            'layout' => 'col-12',
            'break' => true,
        ],
        [
            'id' => 'address',
            'label' => ilangs('Address'),
            'type' => 'textarea',
            'std' => '',
            'layout' => 'col-12',
            'break' => true,
        ],
    ]
];