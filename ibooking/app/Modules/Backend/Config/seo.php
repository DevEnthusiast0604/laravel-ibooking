<?php
return [
    'services' => [
        'post' => [
            'id' => 'post',
            'check_enable' => false,
        ],
        'page' => [
            'id' => 'page',
            'check_enable' => false,
        ],
        'hotel' => [
            'id' => 'hotel',
            'check_enable' => true,
        ],
        'apartment' => [
            'id' => 'apartment',
            'check_enable' => true,
        ],
        'car' => [
            'id' => 'car',
            'check_enable' => true,
        ],
        'space' => [
            'id' => 'space',
            'check_enable' => true,
        ],
        'tour' => [
            'id' => 'tour',
            'check_enable' => true,
        ],
        'beauty' => [
            'id' => 'beauty',
            'check_enable' => true,
        ]
    ],
    'posts_per_page' => 500,
    'page' => [
        'items' => [
            'home' => [
                'id' => 'home',
                'label' => ilangs('Home Page'),
                'route' => '/',
                'check_enable' => false,
            ],
            'hotel' => [
                'id' => 'hotel',
                'label' => ilangs('Hotel Page'),
                'route' => 'hotel',
                'check_enable' => 'hotel',
            ],
            'apartment' => [
                'id' => 'apartment',
                'label' => ilangs('Apartment Page'),
                'route' => 'apartment',
                'check_enable' => 'apartment',
            ],
            'car' => [
                'id' => 'car',
                'label' => ilangs('Car Page'),
                'route' => 'car',
                'check_enable' => 'car',
            ],
            'space' => [
                'id' => 'space',
                'label' => ilangs('Space Page'),
                'route' => 'space',
                'check_enable' => 'space',
            ],
            'beauty-services' => [
                'id' => 'beauty',
                'label' => ilangs('Beauty Page'),
                'route' => 'beauty-services',
                'check_enable' => 'beauty',
            ],
            'tour' => [
                'id' => 'tour',
                'label' => ilangs('Tour Page'),
                'route' => 'tour',
                'check_enable' => 'tour',
            ],
            'hotel-search' => [
                'id' => 'hotel_search',
                'label' => ilangs('Hotel Search Page'),
                'route' => 'hotel-search',
                'check_enable' => 'hotel',
            ],
            'apartment-search' => [
                'id' => 'apartment_search',
                'label' => ilangs('Apartment Search Page'),
                'route' => 'apartment-search',
                'check_enable' => 'apartment',
            ],
            'car-search' => [
                'id' => 'car_search',
                'label' => ilangs('Car Search Page'),
                'route' => 'car-search',
                'check_enable' => 'car',
            ],
            'space-search' => [
                'id' => 'space_search',
                'label' => ilangs('Space Search Page'),
                'route' => 'space-search',
                'check_enable' => 'space',
            ],
            'beauty-search' => [
                'id' => 'beauty_search',
                'label' => ilangs('Beauty Search Page'),
                'route' => 'beauty-search',
                'check_enable' => 'beauty',
            ],
            'tour-search' => [
                'id' => 'tour_search',
                'label' => ilangs('Tour Search Page'),
                'route' => 'tour-search',
                'check_enable' => 'tour',
            ],
            'blog' => [
                'id' => 'blog',
                'label' => ilangs('Blog Page'),
                'route' => 'blog'
            ],
            'become-a-partner' => [
                'id' => 'become_partner',
                'label' => ilangs('Become a Partner Page'),
                'route' => 'become-a-partner'
            ],
            'contact-us' => [
                'id' => 'contact_page',
                'label' => ilangs('Contact Us Page'),
                'route' => 'contact-us'
            ],
        ],
        'fields' => [
            'general' => [
                [
                    'id' => 'seo_title',
                    'label' => ilangs('SEO Title'),
                    'type' => 'text',
                    'std' => '',
                    'break' => true,
                    'translation' => true,
                ],
                [
                    'id' => 'meta_description',
                    'label' => ilangs('Meta Description'),
                    'type' => 'textarea',
                    'rows' => 5,
                    'break' => true,
                    'translation' => true,
                ]
            ],
            'facebook' => [
                [
                    'id' => 'seo_image_facebook',
                    'label' => ilangs('Facebook Image'),
                    'type' => 'image',
                    'break' => true,
                    'breakpoints' => 'xs sm'
                ],
                [
                    'id' => 'seo_title_facebook',
                    'label' => ilangs('Facebook Title'),
                    'type' => 'text',
                    'std' => '',
                    'break' => true,
                    'translation' => true,
                ],
                [
                    'id' => 'meta_description_facebook',
                    'label' => ilangs('Facebook Description'),
                    'type' => 'textarea',
                    'rows' => 5,
                    'break' => true,
                    'translation' => true,
                ]
            ],
            'twitter' => [
                [
                    'id' => 'seo_image_twitter',
                    'label' => ilangs('Twitter Image'),
                    'type' => 'image',
                    'break' => true,
                    'breakpoints' => 'xs sm'
                ],
                [
                    'id' => 'seo_title_twitter',
                    'label' => ilangs('Twitter Title'),
                    'type' => 'text',
                    'std' => '',
                    'break' => true,
                    'translation' => true,
                ],
                [
                    'id' => 'meta_description_twitter',
                    'label' => ilangs('Twitter Description'),
                    'type' => 'textarea',
                    'rows' => 5,
                    'break' => true,
                    'translation' => true,
                ]
            ]
        ]
    ],
    'content' => [
        'items' => [
            'category' => [
                'id' => 'category',
                'label' => ilangs('Categories'),
                'route' => 'category'
            ],
            'tag' => [
                'id' => 'tag',
                'label' => ilangs('Tag'),
                'route' => 'tag'
            ],
            'post-single' => [
                'id' => 'single_post',
                'label' => ilangs('Single Post'),
                'route' => 'post-single'
            ],
            'page-single' => [
                'id' => 'single_page',
                'label' => ilangs('Single Page'),
                'route' => 'page-single'
            ],
            'hotel-single' => [
                'id' => 'single_hotel',
                'label' => ilangs('Single Hotel'),
                'route' => 'hotel-single'
            ],
            'apartment-single' => [
                'id' => 'single_apartment',
                'label' => ilangs('Single Apartment'),
                'route' => 'apartment-single'
            ],
            'car-single' => [
                'id' => 'single_car',
                'label' => ilangs('Single Car'),
                'route' => 'car-single'
            ],
            'space-single' => [
                'id' => 'single_space',
                'label' => ilangs('Single Space'),
                'route' => 'space-single'
            ],
            'tour-single' => [
                'id' => 'single_tour',
                'label' => ilangs('Single Tour'),
                'route' => 'tour-single'
            ],
            'beauty-single' => [
                'id' => 'single_beauty',
                'label' => ilangs('Single Beauty'),
                'route' => 'beauty-single'
            ]
        ],
        'fields' => [
            'general' => [
                [
                    'id' => 'seo_title',
                    'label' => ilangs('SEO Title'),
                    'type' => 'text',
                    'std' => '',
                    'break' => true,
                    'translation' => true,
                ],
                [
                    'id' => 'meta_description',
                    'label' => ilangs('Meta Description'),
                    'type' => 'textarea',
                    'rows' => 5,
                    'break' => true,
                    'translation' => true,
                ]
            ],
            'facebook' => [
                [
                    'id' => 'seo_image_facebook',
                    'label' => ilangs('Facebook Image'),
                    'type' => 'image',
                    'break' => true,
                    'breakpoints' => 'xs sm'
                ],
                [
                    'id' => 'seo_title_facebook',
                    'label' => ilangs('Facebook Title'),
                    'type' => 'text',
                    'std' => '',
                    'break' => true,
                    'translation' => true,
                ],
                [
                    'id' => 'meta_description_facebook',
                    'label' => ilangs('Facebook Description'),
                    'type' => 'textarea',
                    'rows' => 5,
                    'break' => true,
                    'translation' => true,
                ]
            ],
            'twitter' => [
                [
                    'id' => 'seo_image_twitter',
                    'label' => ilangs('Twitter Image'),
                    'type' => 'image',
                    'break' => true,
                    'breakpoints' => 'xs sm'
                ],
                [
                    'id' => 'seo_title_twitter',
                    'label' => ilangs('Twitter Title'),
                    'type' => 'text',
                    'std' => '',
                    'break' => true,
                    'translation' => true,
                ],
                [
                    'id' => 'meta_description_twitter',
                    'label' => ilangs('Twitter Description'),
                    'type' => 'textarea',
                    'rows' => 5,
                    'break' => true,
                    'translation' => true,
                ]
            ]
        ]
    ]
];