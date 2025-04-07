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
                    'post_type' => 'page',
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
        ]
    ]
];