<?php
return [
   'settings' => [
      [
         'id' => 'general_settings',
         'label' => ilangs('General'),
         'fields' => [
            [
               'id' => 'post_title',
               'label' => ilangs('Name'),
               'type' => 'text',
               'std' => '',
               'break' => true,
               'validation' => 'required',
               'translation' => true,
            ],
            [
               'id' => 'post_content',
               'label' => ilangs('Description'),
               'type' => 'editor',
               'layout' => 'col-12',
               'std' => '',
               'break' => true,
               'translation' => true
            ],
            [
               'id' => 'status',
               'label' => ilangs('Status'),
               'type' => 'select',
               'choices' => 'status:beauty',
               'layout' => 'col-12 col-md-4',
            ]
         ]
      ],
      [
         'id' => 'media_settings',
         'label' => ilangs('Media'),
         'fields' => [
            [
               'id' => 'thumbnail_id',
               'label' => ilangs('Avatar'),
               'type' => 'image',
               'layout' => 'col-6',
               'break' => true,
            ],
         ]
      ],
      [
         'id' => 'custom_price_settings',
         'label' => ilangs('Availability'),
         'fields' => [
            [
               'id' => 'custom_price',
               'label' => ilangs('Custom Pricing'),
               'type' => 'custom_price',
               'std' => '',
               'break' => true,
               'column' => 'col-12',
            ]
         ]
      ],
   ]
];