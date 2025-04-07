<?php
return [
	'settings' => [
		[
			'id' => 'content_settings',
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
                    'post_type' => 'post',
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
                    'id' => 'status',
                    'label' => ilangs('Status'),
                    'type' => 'select',
                    'choices' => [
                        'publish' => ilangs('Publish'),
                        'draft' => ilangs('Draft')
                    ],
                    'layout' => 'col-12 col-md-4',
                    'break' => true
                ],
                [
                    'id' => 'thumbnail_id',
                    'label' => ilangs('Featured Image'),
                    'type' => 'image',
                    'layout' => 'col-12 col-md-6',
                ],
			]
		],
		[
			'id' => 'attribute_settings',
			'label' => ilangs('Attributes'),
			'fields' => [
                [
                    'id' => 'post_category',
                    'label' => ilangs('Categories'),
                    'type' => 'checkbox',
                    'std' => '',
                    'break' => true,
                    'column' => 'col-md-3 col-sm-6 col-12',
                    'translation' => true,
                    'choices' => 'term:name:post-category'
                ],
                [
                    'id' => 'post_tag',
                    'label' => ilangs('Tags'),
                    'type' => 'text',
                    'std' => '',
                    'break' => true,
                ]
			]
		]
	]
];