<?php
/**
 * Created by PhpStorm.
 * User: JreamOQ ( jreamoq@gmail.com )
 * Date: 12/9/20
 * Time: 10:10
 */
return [
    'fields' => [
        [
            'id' => 'code',
            'label' => ilangs('Code'),
            'type' => 'text',
            'std' => '',
            'layout' => 'col-md-6 col-12',
            'validation' => 'required'
        ],
        [
            'id' => 'percent',
            'label' => ilangs('Discount(%)'),
            'type' => 'text',
            'std' => '',
            'layout' => 'col-md-6 col-12',
            'validation' => 'required',
        ],
        [
            'id' => 'description',
            'label' => ilangs('Description'),
            'type' => 'textarea',
            'rows' => '4',
            'std' => '',
            'layout' => 'col-12',
            'translation' => true,
        ],
        [
            'id' => 'start_date',
            'label' => ilangs('Start Date'),
            'type' => 'date_picker',
            'std' => '',
            'layout' => 'col-md-6 col-12',
            'validation' => 'required',
        ],
        [
            'id' => 'end_date',
            'label' => ilangs('End Date'),
            'type' => 'date_picker',
            'std' => '',
            'layout' => 'col-md-6 col-12',
            'validation' => 'required',
        ],
    ]
];