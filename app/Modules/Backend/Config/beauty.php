<?php
return [
   'settings' => [
      [
         'id' => 'general_settings',
         'label' => ilangs('General'),
         'fields' => [
            [
               'id' => 'post_title',
               'label' => ilangs('Title'),
               'type' => 'text',
               'std' => '',
               'break' => true,
               'validation' => 'required',
               'translation' => true,
            ],
            [
               'id' => 'post_slug',
               'label' => ilangs('Permalink'),
               'type' => 'permalink',
               'post_type' => GMZ_SERVICE_BEAUTY,
               'break' => true,
            ],
            [
               'id' => 'post_content',
               'label' => ilangs('Content'),
               'type' => 'editor',
               'layout' => 'col-12',
               'std' => '',
               'break' => true,
               'translation' => true
            ],
             [
                 'id' => 'post_description',
                 'label' => ilangs('Short Description'),
                 'type' => 'textarea',
                 'layout' => 'col-12',
                 'std' => '',
                 'break' => true,
                 'translation' => true
             ],
             [
                 'id' => 'is_featured',
                 'label' => ilangs('Is Featured'),
                 'type' => 'switcher',
                 'std' => 'on',
                 'break' => true,
             ],
            [
               'id' => 'base_price',
               'label' => ilangs('Base Price'),
               'type' => 'text',
               'std' => '',
               'validation' => 'required',
               'layout' => 'col-md-4 col-sm-6 col-12',
            ],
            [
               'id' => 'status',
               'label' => ilangs('Status'),
               'type' => 'select',
               'choices' => 'status:beauty',
               'layout' => 'col-md-4 col-sm-6 col-12',
            ],
             [
                 'id' => 'booking_form',
                 'label' => ilangs('Booking Form'),
                 'type' => 'select',
                 'std' => 'instant',
                 'layout' => 'col-sm-4 col-12',
                 'choices' => [
                     'instant' => ilangs('Instant'),
                     'enquiry' => ilangs('Enquiry'),
                     'both' => ilangs('Instant & Enquiry')
                 ]
             ],
             [
                 'id' => 'external_booking',
                 'label' => ilangs('Enable External Booking'),
                 'type' => 'switcher',
                 'std' => 'off',
                 'break' => true,
             ],
             [
                 'id' => 'external_link',
                 'label' => ilangs('External Link'),
                 'type' => 'text',
                 'break' => true,
                 'layout' => 'col-sm-6 col-12',
                 'condition' => 'external_booking:on'
             ],
         ]
      ],
      [
         'id' => 'attribute_settings',
         'label' => ilangs('Attribute'),
         'fields' => [
            [
               'id' => 'service',
               'label' => ilangs('Select Category'),
               'type' => 'select',
               'std' => '',
               'layout' => 'col-md-6 col-sm-6 col-12',
               'choices' => 'term:name:beauty-services'
            ],
            [
               'id' => 'branch',
               'label' => ilangs('Select Branch'),
               'type' => 'select_with_metadata_term',
               'std' => '',
               'layout' => 'col-md-6 col-sm-6 col-12',
               'choices' => 'term:name:beauty-branch'
            ],
            [
               'id' => 'available_space',
               'label' => ilangs('Available space per service'),
               'type' => 'number',
               'std' => '3',
               'layout' => 'col-md-6 col-sm-6 col-12',
            ],
            [
               'id' => 'service_starts',
               'label' => ilangs('Service Starts'),
               'type' => 'time_picker',
               'std' => '09:00',
               'layout' => 'col-md-6 col-sm-6 col-12'
            ],
            [
               'id' => 'service_ends',
               'label' => ilangs('Service Ends'),
               'type' => 'time_picker',
               'std' => '18:00',
               'layout' => 'col-md-6 col-sm-6 col-12',
            ],
            [
               'id' => 'service_duration',
               'label' => ilangs('Service Duration'),
               'type' => 'time_picker',
               'std' => '01:00',
               'layout' => 'col-md-6 col-sm-6 col-12',
            ],
            [
               'id' => 'agent',
               'label' => ilangs('Select Agent'),
               'type' => 'multi-select2',
               'std' => '',
               'break' => true,
               'layout' => 'col-12',
               'choices' => 'agent'
            ],
            [
               'id' => 'day_off_week',
               'label' => ilangs('Days off of the week'),
               'type' => 'multi-select2',
               'std' => '',
               'break' => true,
               'layout' => 'col-12',
               'choices' => 'day_off_week'
            ],
         ]
      ],
      [
         'id' => 'custom_price_settings',
         'label' => ilangs('Custom Price'),
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
      [
         'id' => 'media_settings',
         'label' => ilangs('Media'),
         'fields' => [
            [
               'id' => 'thumbnail_id',
               'label' => ilangs('Featured Image'),
               'type' => 'image',
               'layout' => 'col-6',
               'break' => true,
            ],
            [
               'id' => 'gallery',
               'label' => ilangs('Gallery'),
               'type' => 'gallery',
               'layout' => 'col-12',
               'break' => true,
            ],
            [
               'id' => 'video',
               'label' => ilangs('Video'),
               'type' => 'text',
               'layout' => 'col-12 col-md-6',
               'break' => true,
            ]
         ]
      ],
      [
         'id' => 'policy_settings',
         'label' => ilangs('Policies'),
         'fields' => [
            [
               'id' => 'enable_cancellation',
               'label' => ilangs('Enable Cancellation'),
               'type' => 'switcher',
               'std' => 'off',
               'break' => true,
            ],
            [
               'id' => 'cancel_before',
               'label' => ilangs('Cancel Before (Days)'),
               'type' => 'number',
               'std' => '5',
               'break' => true,
               'min_max_step' => [1, 30, 1],
               'condition' => 'enable_cancellation:on'
            ],
            [
               'id' => 'cancellation_detail',
               'label' => ilangs('Cancellation Detail'),
               'type' => 'textarea',
               'break' => true,
               'translation' => true,
               'rows' => '5',
               'condition' => 'enable_cancellation:on'
            ]
         ]
      ]
   ]
];