<?php
return [
    'prefix' => 'dashboard',
    'key_hashing' => '20190301',
    'post_types' => [
        'post' => [
            'singular' => ilangs('Post'),
            'plural' => ilangs('Posts'),
            'slug' => 'post'
        ],
        'page' => [
            'singular' => ilangs('Page'),
            'plural' => ilangs('Pages'),
            'slug' => 'page'
        ],
        'car' => [
            'singular' => ilangs('Car'),
            'plural' => ilangs('Cars'),
            'slug' => 'car'
        ],
        'apartment' => [
            'singular' => ilangs('Apartment'),
            'plural' => ilangs('Apartments'),
            'slug' => 'apartment'
        ],
        'tour' => [
            'singular' => ilangs('Tour'),
            'plural' => ilangs('Tours'),
            'slug' => 'tour'
        ],
        'hotel' => [
            'singular' => ilangs('Hotel'),
            'plural' => ilangs('Hotels'),
            'slug' => 'hotel'
        ],
        'space' => [
            'singular' => ilangs('Space'),
            'plural' => ilangs('Space'),
            'slug' => 'space'
        ],
        'beauty' => [
            'singular' => ilangs('Beauty Services'),
            'plural' => ilangs('Beauty'),
            'slug' => 'beauty'
        ],
        'agent' => [
            'singular' => ilangs('Agent'),
            'plural' => ilangs('Agent'),
            'slug' => 'agent'
        ]
    ],
    'max_file_size' => 2,
    'menu_location' => [
        'primary' => ilangs('Primary')
    ],
    'post_status' => [
        'publish' => ilangs('Publish'),
        'draft' => ilangs('Draft'),
        'trash' => ilangs('Trash'),
    ],
    'page_status' => [
        'publish' => ilangs('Publish'),
        'draft' => ilangs('Draft'),
        'trash' => ilangs('Trash'),
    ],
    'apartment_status' => [
        'publish' => ilangs('Publish'),
        'draft' => ilangs('Draft'),
        'pending' => ilangs('Pending'),
        'trash' => ilangs('Trash'),
    ],
    'tour_status' => [
        'publish' => ilangs('Publish'),
        'draft' => ilangs('Draft'),
        'pending' => ilangs('Pending'),
        'trash' => ilangs('Trash'),
    ],
    'car_status' => [
        'publish' => ilangs('Publish'),
        'draft' => ilangs('Draft'),
        'pending' => ilangs('Pending'),
        'trash' => ilangs('Trash'),
    ],
    'hotel_status' => [
        'publish' => ilangs('Publish'),
        'draft' => ilangs('Draft'),
        'pending' => ilangs('Pending'),
        'trash' => ilangs('Trash'),
    ],
    'room_status' => [
        'publish' => ilangs('Publish'),
        'draft' => ilangs('Draft'),
        'pending' => ilangs('Pending'),
        'trash' => ilangs('Trash'),
    ],
    'space_status' => [
        'publish' => ilangs('Publish'),
        'draft' => ilangs('Draft'),
        'pending' => ilangs('Pending'),
        'trash' => ilangs('Trash'),
    ],
    'agent_status' => [
        'publish' => ilangs('Publish'),
        'draft' => ilangs('Draft'),
        'pending' => ilangs('Pending'),
    ],
    'beauty_status' => [
        'publish' => ilangs('Publish'),
        'draft' => ilangs('Draft'),
        'pending' => ilangs('Pending'),
        'trash' => ilangs('Trash'),
    ],
    'admin_menu' => [
        'heading_general' => [
            'type' => 'heading',
            'label' => ilangs('General')
        ],
        'dashboard' => [
            'type' => 'item',
            'label' => 'Dashboard',
            'icon' => 'fa-tachometer-alt',
            'screen' => 'dashboard',
        ],
        'your_profile' => [
            'type' => 'item',
            'label' => ilangs('Your Profile'),
            'icon' => 'fa-user-circle',
            'screen' => 'profile',
        ],
        'notification' => [
            'type' => 'item',
            'label' => ilangs('Notifications'),
            'icon' => 'fa-bell',
            'screen' => 'notifications',
        ],
        'my_order' => [
            'type' => 'item',
            'label' => ilangs('My Orders'),
            'icon' => 'fa-ballot-check',
            'screen' => 'my-orders',
        ],
        'wishlist' => [
            'type' => 'item',
            'label' => ilangs('Wishlist'),
            'icon' => 'fa-heart',
            'screen' => 'wishlist',
        ],
        'earning_report' => [
            'type' => 'parent',
            'label' => ilangs('Earnings Report'),
            'icon' => 'fa-file-chart-line',
            'id' => 'report',
            'child' => [
                [
                    'type' => 'item',
                    'label' => __("Partner's Earnings "),
                    'icon' => 'icon_system_communication',
                    'screen' => 'partner-earnings',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Analytics'),
                    'icon' => 'icon_system_communication',
                    'screen' => 'analytics',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Withdrawal'),
                    'icon' => 'icon_system_communication',
                    'screen' => 'withdrawal',
                ]
            ],
            'screen' => ['partner-earnings', 'analytics', 'withdrawal'],
        ],
        /*post*/
        'post' => [
            'type' => 'parent',
            'label' => ilangs('Post'),
            'icon' => 'fa-sticky-note',
            'id' => 'post',
            'child' => [
                [
                    'type' => 'item',
                    'label' => ilangs('All Posts'),
                    'screen' => 'all-posts',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Add new'),
                    'screen' => 'new-post',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Category'),
                    'screen' => 'term/post-category',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Tag'),
                    'screen' => 'term/post-tag',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Comments'),
                    'screen' => 'comment',
                ]
            ],
            'screen' => ['all-posts', 'new-post', 'edit-post', 'term/post-category', 'term/post-tag', 'comment', 'new-term/post-category', 'edit-term/post-category', 'new-term/post-tag', 'edit-term/post-tag']
        ],
        /*page*/
        'page' => [
            'type' => 'parent',
            'label' => ilangs('Page'),
            'icon' => 'fa-sticky-note',
            'id' => 'page',
            'child' => [
                [
                    'type' => 'item',
                    'label' => ilangs('All Pages'),
                    'screen' => 'all-pages',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Add new'),
                    'screen' => 'new-page',
                ]
            ],
            'screen' => ['all-pages', 'new-page', 'edit-page']
        ],
        'heading_service' => [
            'type' => 'heading',
            'services' => ['car', 'apartment', 'hotel', 'space', 'tour'],
            'label' => ilangs('All Services')
        ],
        /*hotel*/
        'hotel' => [
            'type' => 'parent',
            'label' => ilangs('Hotel'),
            'icon' => 'fa-building',
            'id' => 'hotel',
            'service' => 'hotel',
            'child' => [
                [
                    'type' => 'item',
                    'label' => ilangs('All Hotels'),
                    'screen' => 'all-hotels',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Add new Hotel'),
                    'screen' => 'new-hotel',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Property Types'),
                    'screen' => 'term/property-type',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Facilities'),
                    'screen' => 'term/hotel-facilities',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Hotel Services'),
                    'screen' => 'term/hotel-services',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Room Facilities'),
                    'screen' => 'term/room-facilities',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Reviews'),
                    'screen' => 'hotel-review',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Orders'),
                    'screen' => 'order/hotel',
                ],
            ],
            'screen' => ['all-hotels', 'new-hotel', 'edit-hotel', 'term/property-type', 'term/hotel-facilities', 'term/hotel-services', 'term/room-facilities', 'hotel-review', 'order/hotel', 'all-rooms', 'edit-room', 'new-term/property-type', 'edit-term/property-type', 'new-term/hotel-facilities', 'edit-term/hotel-facilities', 'new-term/hotel-services', 'edit-term/hotel-services', 'new-term/room-facilities', 'edit-term/room-facilities']
        ],
        /*apartments*/
        'apartment' => [
            'type' => 'parent',
            'label' => ilangs('Apartment'),
            'icon' => 'fa-city',
            'id' => 'apartment',
            'service' => 'apartment',
            'child' => [
                [
                    'type' => 'item',
                    'label' => ilangs('All Apartments'),
                    'screen' => 'all-apartments',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Add new'),
                    'screen' => 'new-apartment',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Types'),
                    'screen' => 'term/apartment-type',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Amenities'),
                    'screen' => 'term/apartment-amenity',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Reviews'),
                    'screen' => 'apartment-review',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Orders'),
                    'screen' => 'order/apartment',
                ],
            ],
            'screen' => ['all-apartments', 'new-apartment', 'edit-apartment', 'term/apartment-type', 'term/apartment-amenity', 'apartment-review', 'order/apartment', 'new-term/apartment-type', 'edit-term/apartment-type', 'new-term/apartment-amenity', 'edit-term/apartment-amenity']
        ],
        /*car*/
        'car' => [
            'type' => 'parent',
            'label' => ilangs('Car'),
            'icon' => 'fa-car',
            'id' => 'car',
            'service' => 'car',
            'child' => [
                [
                    'type' => 'item',
                    'label' => ilangs('All Cars'),
                    'screen' => 'all-cars',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Add new'),
                    'screen' => 'new-car',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Types'),
                    'screen' => 'term/car-type',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Features'),
                    'screen' => 'term/car-feature',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Equipments'),
                    'screen' => 'term/car-equipment',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Reviews'),
                    'screen' => 'car-review',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Orders'),
                    'screen' => 'order/car',
                ],
            ],
            'screen' => ['all-cars', 'new-car', 'edit-car', 'term/car-type', 'term/car-feature', 'term/car-equipment', 'car-review', 'order/car', 'new-term/car-type', 'edit-term/car-type', 'new-term/car-feature', 'edit-term/car-feature', 'new-term/car-equipment', 'edit-term/car-equipment']
        ],
        /*space*/
        'space' => [
            'type' => 'parent',
            'label' => ilangs('Space'),
            'icon' => 'fa-hotel',
            'id' => 'space',
            'service' => 'space',
            'child' => [
                [
                    'type' => 'item',
                    'label' => ilangs('All Space'),
                    'screen' => 'all-spaces',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Add new'),
                    'screen' => 'new-space',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Types'),
                    'screen' => 'term/space-type',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Amenities'),
                    'screen' => 'term/space-amenity',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Reviews'),
                    'screen' => 'space-review',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Orders'),
                    'screen' => 'order/space',
                ],
            ],
            'screen' => ['all-spaces', 'new-space', 'edit-space', 'term/space-type', 'term/space-amenity', 'space-review', 'order/space', 'new-term/space-type', 'edit-term/space-type', 'new-term/space-amenity', 'edit-term/space-amenity']
        ],
        /*tour*/
        'tour' => [
            'type' => 'parent',
            'label' => ilangs('Tour'),
            'icon' => 'fa-pennant',
            'id' => 'tour',
            'service' => 'tour',
            'child' => [
                [
                    'type' => 'item',
                    'label' => ilangs('All Tours'),
                    'screen' => 'all-tours',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Add new'),
                    'screen' => 'new-tour',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Types'),
                    'screen' => 'term/tour-type',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Tour Includes'),
                    'screen' => 'term/tour-include',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Tour Excludes'),
                    'screen' => 'term/tour-exclude',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Reviews'),
                    'screen' => 'tour-review',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Orders'),
                    'screen' => 'order/tour',
                ],
            ],
            'screen' => ['all-tours', 'new-tour', 'edit-tour', 'term/tour-type', 'term/tour-include', 'term/tour-exclude', 'tour-review', 'order/tour', 'new-term/tour-type', 'edit-term/tour-type', 'new-term/tour-include', 'edit-term/tour-include', 'new-term/tour-exclude', 'edit-term/tour-exclude']
        ],
        /*beauty*/
        'beauty' => [
            'type' => 'parent',
            'label' => ilangs('Beauty Services'),
            'icon' => 'fa-spa',
            'id' => 'beauty',
            'service' => 'beauty',
            'child' => [
                [
                    'type' => 'item',
                    'label' => ilangs('All Beauty'),
                    'screen' => 'all-beauty',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Add new'),
                    'screen' => 'new-beauty',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Service Categories'),
                    'screen' => 'term/beauty-services',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Branch'),
                    'screen' => 'term/beauty-branch',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Agent'),
                    'screen' => 'beauty/all-agents',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Reviews'),
                    'screen' => 'beauty-review',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Orders'),
                    'screen' => 'order/beauty',
                ],
            ],
            'screen' => ['all-beauty', 'new-beauty', 'edit-beauty', 'term/beauty-services', 'beauty-review', 'order/beauty', 'beauty/all-agents', 'beauty/new-agent', 'beauty/edit-agent', 'term/beauty-branch']
        ],
        'heading_system' => [
            'type' => 'heading',
            'label' => ilangs('System')
        ],
        'settings' => [
            'type' => 'item',
            'label' => ilangs('Settings'),
            'icon' => 'fa-cog',
            'screen' => 'settings',
        ],
        'menu' => [
            'type' => 'item',
            'label' => ilangs('Menus'),
            'icon' => 'fa-bars',
            'screen' => 'menu',
        ],
        'user' => [
            'type' => 'parent',
            'label' => ilangs('Users'),
            'icon' => 'fa-user-friends',
            'id' => 'user',
            'child' => [
                [
                    'type' => 'item',
                    'label' => ilangs('All Users'),
                    'screen' => 'all-users',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Partner Request'),
                    'screen' => 'partner-request',
                ]
            ],
            'screen' => ['all-users', 'partner-request']
        ],
        'media' => [
            'type' => 'item',
            'label' => ilangs('Media'),
            'icon' => 'fa-images',
            'screen' => 'all-media',
        ],
        'language' => [
            'type' => 'parent',
            'label' => ilangs('Language'),
            'icon' => 'fa-globe-americas',
            'id' => 'language',
            'child' => [
                [
                    'type' => 'item',
                    'label' => ilangs('Setup'),
                    'screen' => 'language',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Translation'),
                    'screen' => 'translation',
                ]
            ],
            'screen' => ['language', 'translation']
        ],
        'coupon' => [
            'type' => 'item',
            'label' => ilangs('Coupon'),
            'icon' => 'fa-sticky-note',
            'screen' => 'coupon',
        ],
        'tool' => [
            'type' => 'parent',
            'label' => ilangs('Tools'),
            'icon' => 'fa-tools',
            'id' => 'tools',
            'child' => [
                [
                    'type' => 'item',
                    'label' => ilangs('Import Data'),
                    'icon' => 'icon_system_import_02',
                    'screen' => 'import-data',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('SEO'),
                    'icon' => 'icon_system_import_02',
                    'screen' => 'seo',
                ],
            ],
            'screen' => ['import-data', 'seo']
        ],

    ],
    'partner_menu' => [
        'heading_general' => [
            'type' => 'heading',
            'label' => ilangs('General')
        ],
        'dashboard' => [
            'type' => 'item',
            'label' => 'Dashboard',
            'icon' => 'fa-tachometer-alt',
            'screen' => 'dashboard',
        ],
        'your_profile' => [
            'type' => 'item',
            'label' => ilangs('Your Profile'),
            'icon' => 'fa-user-circle',
            'screen' => 'profile',
        ],
        'notification' => [
            'type' => 'item',
            'label' => ilangs('Notifications'),
            'icon' => 'fa-bell',
            'screen' => 'notifications',
        ],
        'my_order' => [
            'type' => 'item',
            'label' => ilangs('My Orders'),
            'icon' => 'fa-ballot-check',
            'screen' => 'my-orders',
        ],
        'wishlist' => [
            'type' => 'item',
            'label' => ilangs('Wishlist'),
            'icon' => 'fa-heart',
            'screen' => 'wishlist',
        ],
        'earning_report' => [
            'type' => 'parent',
            'label' => ilangs('Earnings Report'),
            'icon' => 'fa-file-chart-line',
            'id' => 'report',
            'screen' => ['analytics'],
            'child' => [
                [
                    'type' => 'item',
                    'label' => ilangs('Analytics'),
                    'icon' => 'icon_system_communication',
                    'screen' => 'analytics',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Withdrawal'),
                    'icon' => 'icon_system_communication',
                    'screen' => 'withdrawal',
                ]
            ],
        ],
        'heading_service' => [
            'type' => 'heading',
            'services' => ['car', 'apartment', 'hotel', 'space', 'tour'],
            'label' => ilangs('All Services')
        ],
        'hotel' => [
            'type' => 'parent',
            'label' => ilangs('Hotel'),
            'icon' => 'fa-building',
            'id' => 'hotel',
            'service' => 'hotel',
            'child' => [
                [
                    'type' => 'item',
                    'label' => ilangs('All Hotels'),
                    'screen' => 'all-hotels',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Add new Hotel'),
                    'screen' => 'new-hotel',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Reviews'),
                    'screen' => 'hotel-review',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Orders'),
                    'screen' => 'order/hotel',
                ],
            ],
            'screen' => ['all-hotels', 'new-hotel', 'edit-hotel', 'hotel-review', 'order/hotel', 'all-rooms', 'edit-room']
        ],
        'apartment' => [
            'type' => 'parent',
            'label' => ilangs('Apartment'),
            'icon' => 'fa-city',
            'id' => 'apartment',
            'service' => 'apartment',
            'child' => [
                [
                    'type' => 'item',
                    'label' => ilangs('All Apartments'),
                    'screen' => 'all-apartments',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Add new'),
                    'screen' => 'new-apartment',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Reviews'),
                    'screen' => 'apartment-review',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Orders'),
                    'screen' => 'order/apartment',
                ],
            ],
            'screen' => ['all-apartments', 'new-apartment', 'edit-apartment', 'apartment-review', 'order/apartment']
        ],
        'car' => [
            'type' => 'parent',
            'label' => ilangs('Car'),
            'icon' => 'fa-car',
            'id' => 'car',
            'service' => 'car',
            'child' => [
                [
                    'type' => 'item',
                    'label' => ilangs('All Cars'),
                    'screen' => 'all-cars',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Add new'),
                    'screen' => 'new-car',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Reviews'),
                    'screen' => 'car-review',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Orders'),
                    'screen' => 'order/car',
                ],
            ],
            'screen' => ['all-cars', 'new-car', 'edit-car', 'car-review', 'order/car']
        ],
        'space' => [
            'type' => 'parent',
            'label' => ilangs('Space'),
            'icon' => 'fa-hotel',
            'id' => 'space',
            'service' => 'space',
            'child' => [
                [
                    'type' => 'item',
                    'label' => ilangs('All Space'),
                    'screen' => 'all-spaces',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Add new'),
                    'screen' => 'new-space',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Reviews'),
                    'screen' => 'space-review',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Orders'),
                    'screen' => 'order/space',
                ],
            ],
            'screen' => ['all-spaces', 'new-space', 'edit-space', 'space-review', 'order/space']
        ],
        'tour' => [
            'type' => 'parent',
            'label' => ilangs('Tour'),
            'icon' => 'fa-pennant',
            'id' => 'tour',
            'service' => 'tour',
            'child' => [
                [
                    'type' => 'item',
                    'label' => ilangs('All Tours'),
                    'screen' => 'all-tours',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Add new'),
                    'screen' => 'new-tour',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Reviews'),
                    'screen' => 'tour-review',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Orders'),
                    'screen' => 'order/tour',
                ],
            ],
            'screen' => ['all-tours', 'new-tour', 'edit-tour', 'tour-review', 'order/tour']
        ],
        'beauty' => [
            'type' => 'parent',
            'label' => ilangs('Beauty Services'),
            'icon' => 'fa-spa',
            'id' => 'beauty',
            'service' => 'beauty',
            'child' => [
                [
                    'type' => 'item',
                    'label' => ilangs('All Beauty'),
                    'screen' => 'all-beauty',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Add new'),
                    'screen' => 'new-beauty',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Branch'),
                    'screen' => 'term/beauty-branch',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Agent'),
                    'screen' => 'beauty/all-agents',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Reviews'),
                    'screen' => 'beauty-review',
                ],
                [
                    'type' => 'item',
                    'label' => ilangs('Orders'),
                    'screen' => 'order/beauty',
                ],
            ],
            'screen' => ['all-beauty', 'new-beauty', 'edit-beauty', 'term/beauty-services', 'beauty-review', 'order/beauty', 'beauty/all-agents', 'beauty/new-agent', 'beauty/edit-agent', 'term/beauty-branch']
        ],
        'heading_system' => [
            'type' => 'heading',
            'label' => ilangs('System')
        ],
        'media' => [
            'type' => 'item',
            'label' => ilangs('Media'),
            'icon' => 'fa-images',
            'screen' => 'all-media',
        ],
    ],
    'customer_menu' => [
        'dashboard' => [
            'type' => 'item',
            'label' => 'Dashboard',
            'icon' => 'fa-tachometer-alt',
            'screen' => 'dashboard',
        ],
        'your_profile' => [
            'type' => 'item',
            'label' => ilangs('Your Profile'),
            'icon' => 'fa-user-circle',
            'screen' => 'profile',
        ],
        'notification' => [
            'type' => 'item',
            'label' => ilangs('Notifications'),
            'icon' => 'fa-bell',
            'screen' => 'notifications',
        ],
        'my_order' => [
            'type' => 'item',
            'label' => ilangs('My Orders'),
            'icon' => 'fa-ballot-check',
            'screen' => 'my-orders',
        ],
        'wishlist' => [
            'type' => 'item',
            'label' => ilangs('Wishlist'),
            'icon' => 'fa-heart',
            'screen' => 'wishlist',
        ]
    ]
];