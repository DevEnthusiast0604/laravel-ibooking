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
                    'post_type' => GMZ_SERVICE_HOTEL,
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
                    'id' => 'status',
                    'label' => ilangs('Status'),
                    'type' => 'select',
                    'choices' => 'status:hotel',
                    'layout' => 'col-12 col-md-4',
                ],
                [
                    'id' => 'hotel_star',
                    'label' => ilangs('Hotel Star'),
                    'type' => 'select',
                    'std' => '',
                    'layout' => 'col-sm-4 col-12',
                    'choices' => [
                        '5' => ilangs('5 Star'),
                        '4' => ilangs('4 Star'),
                        '3' => ilangs('3 Star'),
                        '2' => ilangs('2 Star'),
                        '1' => ilangs('1 Star'),
                    ]
                ],
                [
                    'id' => 'property_type',
                    'label' => ilangs('Property Type'),
                    'type' => 'select',
                    'std' => '',
                    'layout' => 'col-sm-4 col-12',
                    'choices' => 'term:name:property-type'
                ],
                [
                    'id' => 'booking_form',
                    'label' => ilangs('Booking Form'),
                    'type' => 'select',
                    'std' => 'both',
                    'layout' => 'col-sm-4 col-12',
                    'choices' => [
                        'instant' => ilangs('Instant'),
                        'enquiry' => ilangs('Enquiry'),
                        'both' => ilangs('Instant & Enquiry')
                    ]
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
                ],
                [
                    'id' => 'nearby_common',
                    'label' => ilangs('What\'s Nearby'),
                    'type' => 'list_item',
                    'layout' => 'col-md-6 col-12',
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
                            'id' => 'distance',
                            'label' => ilangs('Distance'),
                            'type' => 'text',
                            'std' => '',
                            'break' => true,
                        ]
                    ]
                ],
                [
                    'id' => 'nearby_education',
                    'label' => ilangs('Education'),
                    'type' => 'list_item',
                    'layout' => 'col-md-6 col-12',
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
                            'id' => 'distance',
                            'label' => ilangs('Distance'),
                            'type' => 'text',
                            'std' => '',
                            'break' => true,
                        ]
                    ]
                ],
                [
                    'id' => 'nearby_health',
                    'label' => ilangs('Health'),
                    'type' => 'list_item',
                    'layout' => 'col-md-6 col-12',
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
                            'id' => 'distance',
                            'label' => ilangs('Distance'),
                            'type' => 'text',
                            'std' => '',
                            'break' => true,
                        ]
                    ]
                ],
                [
                    'id' => 'nearby_top_attractions',
                    'label' => ilangs('Top Attractions'),
                    'type' => 'list_item',
                    'layout' => 'col-md-6 col-12',
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
                            'id' => 'distance',
                            'label' => ilangs('Distance'),
                            'type' => 'text',
                            'std' => '',
                            'break' => true,
                        ]
                    ]
                ],
                [
                    'id' => 'nearby_restaurants_cafes',
                    'label' => ilangs('Restaurants & Cafes'),
                    'type' => 'list_item',
                    'layout' => 'col-md-6 col-12',
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
                            'id' => 'distance',
                            'label' => ilangs('Distance'),
                            'type' => 'text',
                            'std' => '',
                            'break' => true,
                        ]
                    ]
                ],
                [
                    'id' => 'nearby_natural_beauty',
                    'label' => ilangs('Natural Beauty'),
                    'type' => 'list_item',
                    'layout' => 'col-md-6 col-12',
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
                            'id' => 'distance',
                            'label' => ilangs('Distance'),
                            'type' => 'text',
                            'std' => '',
                            'break' => true,
                        ]
                    ]
                ],
                [
                    'id' => 'nearby_airports',
                    'label' => ilangs('Closest Airports'),
                    'type' => 'list_item',
                    'layout' => 'col-md-6 col-12',
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
                            'id' => 'distance',
                            'label' => ilangs('Distance'),
                            'type' => 'text',
                            'std' => '',
                            'break' => true,
                        ]
                    ]
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
                    'id' => 'extra_services',
                    'label' => ilangs('Extra Services'),
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
                            'id' => 'price',
                            'label' => ilangs('Price'),
                            'type' => 'text',
                            'std' => '',
                            'break' => true,
                        ]
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
            'id' => 'amemity_settings',
            'label' => ilangs('Amenities'),
            'fields' => [
                [
                    'id' => 'checkin_time',
                    'label' => ilangs('Checkin Time'),
                    'type' => 'text',
                    'std' => '',
                    'layout' => 'col-md-6 col-sm-6 col-12'
                ],
                [
                    'id' => 'checkout_time',
                    'label' => ilangs('Checkout Time'),
                    'type' => 'text',
                    'std' => '',
                    'layout' => 'col-md-6 col-sm-6 col-12'
                ],
                [
                    'id' => 'min_day_booking',
                    'label' => ilangs('Min Day Before Booking'),
                    'type' => 'number',
                    'std' => '1',
                    'layout' => 'col-md-6 col-sm-6 col-12',
                    'min_max_step' => [0, 100, 1],
                ],
                [
                    'id' => 'min_day_stay',
                    'label' => ilangs('Min Day Stays'),
                    'type' => 'number',
                    'std' => '1',
                    'layout' => 'col-md-6 col-sm-6 col-12',
                    'min_max_step' => [1, 100, 1],
                ],
                [
                    'id' => 'hotel_facilities',
                    'label' => ilangs('Hotel Facilities'),
                    'type' => 'checkbox',
                    'std' => '',
                    'break' => true,
                    'column' => 'col-md-4 col-sm-6 col-12',
                    'translation' => true,
                    'choices' => 'term:name:hotel-facilities'
                ],
                [
                    'id' => 'hotel_services',
                    'label' => ilangs('Hotel Services'),
                    'type' => 'checkbox',
                    'std' => '',
                    'break' => true,
                    'column' => 'col-md-4 col-sm-6 col-12',
                    'translation' => true,
                    'choices' => 'term:name:hotel-services'
                ],
                [
                    'id' => 'faq',
                    'label' => ilangs('FAQs'),
                    'type' => 'list_item',
                    'layout' => 'col-md-8 col-12',
                    'break' => true,
                    'translation' => true,
                    'binding' => 'question',
                    'fields' => [
                        [
                            'id' => 'question',
                            'label' => ilangs('Question'),
                            'type' => 'text',
                            'std' => '',
                            'break' => true,
                            'translation' => true,
                        ],
                        [
                            'id' => 'answer',
                            'label' => ilangs('Answer'),
                            'type' => 'textarea',
                            'std' => '',
                            'break' => true,
                            'translation' => true,
                        ]
                    ]
                ],
            ]
        ],
        [
            'id' => 'media_settings',
            'label' => ilangs('Media'),
            'fields' => [
                [
                    'id' => 'hotel_logo',
                    'label' => ilangs('Hotel Logo'),
                    'type' => 'image',
                    'layout' => 'col-6',
                    'break' => true,
                ],
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
                    'id' => 'policy',
                    'label' => ilangs('Policies'),
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
                            'id' => 'content',
                            'label' => ilangs('Content'),
                            'type' => 'textarea',
                            'std' => '',
                            'break' => true,
                            'translation' => true,
                        ]
                    ]
                ],
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