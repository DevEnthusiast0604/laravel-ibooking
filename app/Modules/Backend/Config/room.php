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
					'id' => 'post_content',
					'label' => ilangs('Content'),
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
                    'choices' => 'status:room',
                    'layout' => 'col-12 col-md-6',
                ],
			]
		],
        'attribute' => [
            'id' => 'attribute_settings',
            'label' => ilangs('Attribute'),
            'fields' => [
                [
                    'id' => 'base_price',
                    'label' => ilangs('Price'),
                    'type' => 'text',
                    'std' => '',
                    'validation' => 'required',
                    'layout' => 'col-12 col-md-4',
                ],
                [
                    'id' => 'room_footage',
                    'label' => ilangs('Room Footage'),
                    'type' => 'text',
                    'std' => '',
                    'validation' => 'required',
                    'layout' => 'col-12 col-md-4',
                ],
                [
                    'id' => 'number_of_room',
                    'label' => ilangs('Number Of Room'),
                    'type' => 'number',
                    'std' => '1',
                    'validation' => 'required',
                    'layout' => 'col-12 col-md-4',
                    'min_max_step' => [1, 100, 1],
                ],
                [
                    'id' => 'number_of_bed',
                    'label' => ilangs('Number Of Bed'),
                    'type' => 'number',
                    'std' => '1',
                    'validation' => 'required',
                    'layout' => 'col-12 col-md-4',
                    'min_max_step' => [1, 100, 1],
                ],
                [
                    'id' => 'number_of_adult',
                    'label' => ilangs('Number Of Adult'),
                    'type' => 'number',
                    'std' => '1',
                    'validation' => 'required',
                    'layout' => 'col-12 col-md-4',
                    'min_max_step' => [1, 100, 1],
                ],
                [
                    'id' => 'number_of_children',
                    'label' => ilangs('Number Of Children'),
                    'type' => 'number',
                    'std' => '1',
                    'layout' => 'col-12 col-md-4',
                    'min_max_step' => [0, 100, 1],
                ],
                [
                    'id' => 'room_facilities',
                    'label' => ilangs('Room Facilities'),
                    'type' => 'checkbox',
                    'std' => '',
                    'break' => true,
                    'column' => 'col-md-4 col-sm-6 col-12',
                    'translation' => true,
                    'choices' => 'term:name:room-facilities'
                ]
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
            ]
        ],
        'custom_price' => [
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