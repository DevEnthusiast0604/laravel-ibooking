<?php
return [
	'settings' => [
		'general' => [
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
                    'post_type' => GMZ_SERVICE_TOUR,
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
                    'choices' => 'status:tour',
                    'layout' => 'col-12 col-md-4',
                ],
                [
                    'id' => 'tour_type',
                    'label' => ilangs('Type'),
                    'type' => 'select',
                    'std' => '',
                    'layout' => 'col-sm-4 col-12',
                    'choices' => 'term:name:tour-type',
                    'break' => true,
                ],
                [
                    'id' => 'duration',
                    'label' => ilangs('Duration'),
                    'type' => 'text',
                    'layout' => 'col-sm-4 col-12',
                    'validation' => 'required',
                    'translation' => true,
                ],
                [
                    'id' => 'group_size',
                    'label' => ilangs('Group Size'),
                    'type' => 'number',
                    'min_max_step' => [1, 100, 1],
                    'layout' => 'col-12 col-sm-4',
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
			]
		],
        'location' => [
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
        'pricing' => [
            'id' => 'pricing_settings',
            'label' => ilangs('Pricing'),
            'fields' => [
                [
                    'id' => 'adult_price',
                    'label' => ilangs('Adult Price'),
                    'type' => 'text',
                    'std' => '',
                    'validation' => 'required',
                    'layout' => 'col-lg-4 col-md-6 col-sm-6 col-12',
                ],
                [
                    'id' => 'children_price',
                    'label' => ilangs('Children Price'),
                    'type' => 'text',
                    'std' => '',
                    'layout' => 'col-lg-4 col-md-6 col-sm-6 col-12',
                ],
                [
                    'id' => 'infant_price',
                    'label' => ilangs('Infant Price'),
                    'type' => 'text',
                    'std' => '',
                    'layout' => 'col-lg-4 col-md-6 col-sm-6 col-12',
                ],
                [
                    'id' => 'booking_type',
                    'label' => ilangs('Booking Type'),
                    'type' => 'select',
                    'std' => 'date',
                    'layout' => 'col-sm-4 col-12',
                    'choices' => [
                        'date' => ilangs('Date'),
                        'package' => ilangs('Package'),
                        'external_link' => ilangs('External Link')
                    ],
                ],
                [
                    'id' => 'package_start_date',
                    'label' => ilangs('Start Date'),
                    'type' => 'date_picker',
                    'layout' => 'col-md-4 col-12',
                    'condition' => 'booking_type:package'
                ],
                [
                    'id' => 'package_end_date',
                    'label' => ilangs('End Date'),
                    'type' => 'date_picker',
                    'layout' => 'col-md-4 col-12',
                    'condition' => 'booking_type:package'
                ],
                [
                    'id' => 'external_link',
                    'label' => ilangs('External Link'),
                    'type' => 'text',
                    'break' => true,
                    'layout' => 'col-md-8 col-12',
                    'condition' => 'booking_type:external_link'
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
                        ],
                        [
                            'id' => 'required',
                            'label' => ilangs('Required'),
                            'type' => 'switcher',
                            'std' => 'off',
                            'break' => true,
                        ],
                    ]
                ],
            ]
        ],
        'custom_price' => [
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
        'attributes' => [
            'id' => 'attributes_settings',
            'label' => ilangs('Attributes'),
            'fields' => [
                [
                    'id' => 'tour_include',
                    'label' => ilangs('Tour Includes'),
                    'type' => 'checkbox',
                    'std' => '',
                    'break' => true,
                    'column' => 'col-md-4 col-sm-6 col-12',
                    'translation' => true,
                    'choices' => 'term:name:tour-include'
                ],
                [
                    'id' => 'tour_exclude',
                    'label' => ilangs('Tour Excludes'),
                    'type' => 'checkbox',
                    'std' => '',
                    'break' => true,
                    'column' => 'col-md-4 col-sm-6 col-12',
                    'translation' => true,
                    'choices' => 'term:name:tour-exclude'
                ],
                [
                    'id' => 'highlight',
                    'label' => ilangs('HighLight'),
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
                    'id' => 'itinerary',
                    'label' => ilangs('Itinerary'),
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
        'media' => [
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
		'policy' => [
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