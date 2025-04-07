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
                    'post_type' => 'car',
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
                ],
                [
                    'id' => 'status',
                    'label' => ilangs('Status'),
                    'type' => 'select',
                    'choices' => 'status:car',
                    'layout' => 'col-12 col-md-4',
                    'break' => true
                ],
                [
                    'id' => 'car_type',
                    'label' => ilangs('Car Type'),
                    'type' => 'select',
                    'std' => '',
                    'layout' => 'col-sm-4 col-12',
                    'choices' => 'term:name:car-type'
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
                    'id' => 'quantity',
                    'label' => ilangs('Number'),
                    'type' => 'number',
                    'std' => '10',
                    'layout' => 'col-sm-4 col-12',
                    'min_max_step' => [1, 100, 1],
                ],
			]
		],
        [
            'id' => 'location_settings',
            'label' => ilangs('Location'),
            'fields' => [
                [
                    'id' => 'location',
                    'label' => ilangs('Location'),
                    'type' => 'location',
                    'std' => '',
                    'break' => true,
                    'translation_ext' => true,
                    'column' => 'col-lg-3',
                ]
            ]
        ],
        [
            'id' => 'pricing_settings',
            'label' => ilangs('Pricing'),
            'fields' => [
                [
                    'id' => 'base_price',
                    'label' => ilangs('Base Price'),
                    'type' => 'text',
                    'std' => '',
                    'break' => true,
                    'validation' => 'required',
                    'layout' => 'col-lg-4 col-md-6 col-sm-6 col-12',
                ],
                [
                    'id' => 'discount_by_day',
                    'label' => ilangs('Discount By Number Of Day'),
                    'type' => 'list_item',
                    'layout' => 'col-md-8 col-12',
                    'break' => true,
                    'translation' => true,
                    'fields' => [
                        [
                            'id' => 'title',
                            'label' => ilangs('Title'),
                            'type' => 'text',
                            'std' => '',
                            'break' => true,
                            'translation' => true,
                        ],
                        [
                            'id' => 'from',
                            'label' => ilangs('From'),
                            'type' => 'text',
                            'std' => '',
                            'break' => true,
                        ],
                        [
                            'id' => 'to',
                            'label' => ilangs('To'),
                            'type' => 'text',
                            'std' => '',
                            'break' => true,
                        ],
                        [
                            'id' => 'price',
                            'label' => ilangs('Price'),
                            'type' => 'text',
                            'std' => '',
                            'break' => true,
                        ]
                    ]
                ],
                [
                    'id' => 'insurance_plan',
                    'label' => ilangs('Insurance Plan'),
                    'type' => 'list_item',
                    'layout' => 'col-md-8 col-12',
                    'break' => true,
                    'translation' => true,
                    'fields' => [
                        [
                            'id' => 'title',
                            'label' => ilangs('Title'),
                            'type' => 'text',
                            'std' => '',
                            'break' => true,
                            'translation' => true,
                        ],
                        [
                            'id' => 'description',
                            'label' => ilangs('Description'),
                            'type' => 'textarea',
                            'rows' => '3',
                            'std' => '',
                            'break' => true,
                            'translation' => true,
                        ],
                        [
                            'id' => 'price',
                            'label' => ilangs('Price'),
                            'type' => 'text',
                            'std' => '',
                            'break' => true,
                        ],
                        [
                            'id' => 'fixed',
                            'label' => ilangs('Fixed Price'),
                            'type' => 'switcher',
                            'std' => 'off',
                            'break' => true,
                        ],
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
            'id' => 'amemity_settings',
            'label' => ilangs('Amenities'),
            'fields' => [
                [
                    'id' => 'passenger',
                    'label' => ilangs('Passenger'),
                    'type' => 'number',
                    'std' => '4',
                    'layout' => 'col-sm-6 col-12',
                    'min_max_step' => [1, 50, 1],
                ],
                [
                    'id' => 'gear_shift',
                    'label' => ilangs('Gear Shift'),
                    'type' => 'select',
                    'std' => '',
                    'choices' => [
                    	'automatic' => ilangs('Auto'),
	                    'manual' => ilangs('Manual')
                    ],
                    'validation' => 'required',
                    'layout' => 'col-sm-6 col-12',
                ],
                [
                    'id' => 'baggage',
                    'label' => ilangs('Baggage'),
                    'type' => 'number',
                    'std' => '8',
                    'layout' => 'col-sm-6 col-12',
                    'min_max_step' => [1, 50, 1],
                ],
                [
                    'id' => 'door',
                    'label' => ilangs('Door'),
                    'type' => 'number',
                    'std' => '4',
                    'layout' => 'col-sm-6 col-12',
                    'break' => true,
                    'min_max_step' => [1, 50, 1],
                ],
                [
                    'id' => 'car_feature',
                    'label' => ilangs('Car Features'),
                    'type' => 'checkbox',
                    'std' => '',
                    'break' => true,
                    'column' => 'col-md-4 col-sm-6 col-12',
                    'translation' => true,
                    'choices' => 'term:name:car-feature'
                ],
                [
                    'id' => 'equipments',
                    'label' => ilangs('Equipments'),
                    'type' => 'term_price',
                    'std' => '',
                    'break' => true,
                    'column' => 'col-6',
                    'choices' => 'car-equipment',
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