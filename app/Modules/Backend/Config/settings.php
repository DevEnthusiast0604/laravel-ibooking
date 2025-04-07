<?php
return [
    'settings' => [
        'sections' => [
            [
                'id' => 'general_options',
                'label' => ilangs('General'),
            ],
            [
                'id' => 'page_options',
                'label' => ilangs('Pages'),
            ],
            [
                'id' => 'booking_options',
                'label' => ilangs('Booking'),
            ],
            [
                'id' => 'service_options',
                'label' => ilangs('Services')
            ],
            [
                'id' => 'review_options',
                'label' => ilangs('Reviews')
            ],
            [
                'id' => 'appearance_options',
                'label' => ilangs('Appearance')
            ],
            [
                'id' => 'email_options',
                'label' => ilangs('Email')
            ],
            [
                'id' => 'invoice_options',
                'label' => ilangs('Invoice')
            ],
            [
                'id' => 'advanced_options',
                'label' => ilangs('Advanced')
            ]
        ],
        'fields' => [
            [
                'id' => 'general_tab',
                'label' => ilangs('General'),
                'type' => 'tab',
                'layout' => 'col-12 col-md-6',
                'std' => '#e2a03f',
                'break' => true,
                'translation' => true,
                'section' => 'general_options',
                'tabs' => [
                    [
                        'id' => 'general',
                        'heading' => ilangs('General'),
                        'fields' => [
                            [
                                'id' => 'site_name',
                                'label' => ilangs('Site Name'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'iBooking',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'general'
                            ],
                            [
                                'id' => 'site_description',
                                'label' => ilangs('Site Description'),
                                'type' => 'textarea',
                                'layout' => 'col-12 col-md-8',
                                'std' => '',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'general'
                            ],
                            [
                                'id' => 'admin_user',
                                'label' => ilangs('Admin User'),
                                'type' => 'select',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'choices' => 'user:admin',
                                'tab' => 'general'
                            ],
                            [
                                'id' => 'logo',
                                'label' => ilangs('Logo'),
                                'type' => 'image',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'general'
                            ],
                            [
                                'id' => 'favicon',
                                'label' => ilangs('Favicon'),
                                'type' => 'image',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'general'
                            ],
                            [
                                'id' => 'logo-dashboard',
                                'label' => ilangs('Logo dashboard'),
                                'type' => 'image',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'general'
                            ],
                        ]
                    ],
                    [
                        'id' => 'styling',
                        'heading' => ilangs('Styling'),
                        'fields' => [
                            [
                                'id' => 'main_color',
                                'label' => ilangs('Main Color'),
                                'type' => 'color_picker',
                                'layout' => 'col-12 col-md-8',
                                'std' => '#1ea69a',
                                'break' => true,
                                'tab' => 'styling'
                            ],
                            [
                                'id' => 'custom_css',
                                'label' => ilangs('Custom CSS'),
                                'type' => 'css',
                                'layout' => 'col-12',
                                'std' => '',
                                'break' => true,
                                'tab' => 'styling'
                            ],
                        ]
                    ],
                    [
                        'id' => 'other',
                        'heading' => ilangs('Other'),
                        'fields' => [
                            [
                                'id' => 'header_code',
                                'label' => ilangs('Header Code'),
                                'type' => 'textarea',
                                'layout' => 'col-12',
                                'break' => true,
                                'description' => ilangs('You can add custom code to head of page in here. Ex: Google Analytics code'),
                                'rows' => '10',
                                'tab' => 'other'
                            ],
                            [
                                'id' => 'footer_code',
                                'label' => ilangs('Footer Code'),
                                'type' => 'textarea',
                                'layout' => 'col-12',
                                'break' => true,
                                'rows' => '10',
                                'description' => ilangs('You can add custom code to foot of page in here.'),
                                'tab' => 'other'
                            ],
                        ]
                    ],
                ]
            ],
            //Page
            [
                'id' => 'page_tab',
                'label' => ilangs('Pages'),
                'type' => 'tab',
                'layout' => 'col-12 col-md-8',
                'std' => '#e2a03f',
                'break' => true,
                'translation' => true,
                'tabs' => [
                    [
                        'id' => 'home_page',
                        'heading' => ilangs('Home Page'),
                        'fields' => [
                            [
                                'id' => 'home_page_link',
                                'label' => '',
                                'type' => 'link',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'std' => url('/'),
                                'tab' => 'home_page'
                            ],
                            [
                                'id' => 'home_slider_text',
                                'label' => ilangs('Text on slider'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'Enjoy a great ride with ibooking',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'home_page'
                            ],
                            [
                                'id' => 'home_slider',
                                'label' => ilangs('Slider'),
                                'type' => 'gallery',
                                'layout' => 'col-12 col-md-12',
                                'break' => true,
                                'tab' => 'home_page'
                            ],
                            [
                                'id' => 'list_destination',
                                'type' => 'list_item',
                                'label' => ilangs('List Destinations'),
                                'translation' => true,
                                'binding' => 'name',
                                'fields' => [
                                    [
                                        'id' => 'name',
                                        'label' => ilangs('Name'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                        'translation' => true
                                    ],
                                    [
                                        'id' => 'image',
                                        'label' => ilangs('Feature Image'),
                                        'type' => 'image',
                                        'layout' => 'col-12',
                                        'break' => true,
                                    ],
                                    [
                                        'id' => 'lat',
                                        'label' => ilangs('Latitude'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                    ],
                                    [
                                        'id' => 'lng',
                                        'label' => ilangs('Longitude'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                    ]
                                ],
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'home_page'
                            ],
                            [
                                'id' => 'testimonials',
                                'type' => 'list_item',
                                'label' => ilangs('Testimonial'),
                                'translation' => true,
                                'binding' => 'name',
                                'fields' => [
                                    [
                                        'id' => 'name',
                                        'label' => ilangs('Name'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                        'translation' => true
                                    ],
                                    [
                                        'id' => 'content',
                                        'label' => ilangs('Content'),
                                        'type' => 'textarea',
                                        'layout' => 'col-12',
                                        'translation' => true
                                    ],
                                ],
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'home_page'
                            ],
                        ]
                    ],
                    [
                        'id' => 'blog_page',
                        'heading' => ilangs('Blog'),
                        'fields' => [
                            [
                                'id' => 'blog_link',
                                'label' => '',
                                'type' => 'link',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'std' => url('blog'),
                                'tab' => 'blog_page'
                            ],
                            [
                                'id' => 'blog_feature_image',
                                'label' => ilangs('Feature Image On Blog Page'),
                                'type' => 'image',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'blog_page'
                            ]
                        ]
                    ],
                    [
                        'id' => 'contact_page',
                        'heading' => ilangs('Contact'),
                        'fields' => [
                            [
                                'id' => 'contact_link',
                                'label' => '',
                                'type' => 'link',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'std' => url('contact-us'),
                                'tab' => 'contact_page'
                            ],
                            [
                                'id' => 'contact_feature_image',
                                'label' => ilangs('Feature Image On Contact Page'),
                                'type' => 'image',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'contact_page'
                            ],
                            [
                                'id' => 'contact_heading',
                                'label' => ilangs('Heading'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'contact_page'
                            ],
                            [
                                'id' => 'contact_description',
                                'label' => ilangs('Description'),
                                'type' => 'textarea',
                                'rows' => 4,
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'contact_page'
                            ],
                            [
                                'id' => 'contact_address',
                                'label' => ilangs('Address'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'contact_page'
                            ],
                            [
                                'id' => 'contact_phone',
                                'label' => ilangs('Phone'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'contact_page'
                            ],
                            [
                                'id' => 'contact_email',
                                'label' => ilangs('Email'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'contact_page'
                            ],
                            [
                                'id' => 'contact_map_lat',
                                'label' => ilangs('Latitude'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'contact_page'
                            ],
                            [
                                'id' => 'contact_map_lng',
                                'label' => ilangs('Longitude'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'contact_page'
                            ],
                        ]
                    ]
                ],
                'section' => 'page_options',
            ],

            //Booking
            [
                'id' => 'currencies',
                'type' => 'list_item',
                'label' => ilangs('Currencies'),
                'translation' => true,
                'binding' => 'name',
                'fields' => [
                    [
                        'id' => 'name',
                        'label' => ilangs('Name'),
                        'type' => 'text',
                        'layout' => 'col-12 col-md-6',
                        'translation' => true
                    ],
                    [
                        'id' => 'symbol',
                        'label' => ilangs('Symbol'),
                        'type' => 'text',
                        'layout' => 'col-12 col-md-6',
                        'break' => true,
                    ],
                    [
                        'id' => 'unit',
                        'label' => ilangs('Unit'),
                        'type' => 'select',
                        'choices' => default_currencies(),
                        'style' => 'wide',
                        'layout' => 'col-12 col-sm-4',
                    ],
                    [
                        'id' => 'exchange',
                        'label' => ilangs('Exchange Rate'),
                        'type' => 'text',
                        'std' => 1,
                        'layout' => 'col-12 col-sm-4',
                    ],
                    [
                        'id' => 'position',
                        'label' => ilangs('Position'),
                        'type' => 'select',
                        'choices' => [
                            'left' => ilangs('Left ($99)'),
                            'left_space' => ilangs('Left With Space ($ 99)'),
                            'right' => ilangs('Right (99$)'),
                            'right_space' => ilangs('Right With Space (99 $)'),
                        ],
                        'style' => 'wide',
                        'std' => 'left',
                        'layout' => 'col-12 col-sm-4',
                        'break' => true,
                    ],
                    [
                        'id' => 'thousand_separator',
                        'label' => ilangs('Thousand Separator'),
                        'type' => 'text',
                        'std' => ',',
                        'layout' => 'col-12 col-sm-4',
                    ],
                    [
                        'id' => 'decimal_separator',
                        'label' => ilangs('Decimal Separator'),
                        'type' => 'text',
                        'std' => '.',
                        'layout' => 'col-12 col-sm-4',
                    ],
                    [
                        'id' => 'currency_decimal',
                        'label' => ilangs('Currency Decimal'),
                        'type' => 'number',
                        'minlength' => 0,
                        'std' => 0,
                        'layout' => 'col-12 col-sm-4',
                    ],
                ],
                'std' => [
                    [
                        'name' => 'USD',
                        'symbol' => '$',
                        'unit' => 'USD',
                        'exchange' => 1,
                        'position' => 'left',
                        'thousand_separator' => ',',
                        'decimal_separator' => '.',
                        'currency_decimal' => 2
                    ]
                ],
                'layout' => 'col-12 col-md-10',
                'break' => true,
                'section' => 'booking_options'
            ],

            [
                'id' => 'primary_currency',
                'label' => ilangs('Primary Currency'),
                'type' => 'select',
                'choices' => 'currency',
                'std' => 'USD',
                'layout' => 'col-12 col-md-8',
                'style' => 'wide',
                'break' => true,
                'section' => 'booking_options'
            ],
            [
                'id' => 'tax_included',
                'label' => ilangs('Tax is included?'),
                'type' => 'switcher',
                'std' => 'off',
                'section' => 'booking_options'
            ],
            [
                'id' => 'tax_percent',
                'label' => ilangs('Tax (%)'),
                'type' => 'text',
                'std' => '10',
                'layout' => 'col-12 col-md-8',
                'section' => 'booking_options',
            ],
            [
                'id' => 'commission',
                'label' => ilangs('Commission (%)'),
                'type' => 'number',
                'layout' => 'col-12 col-md-8',
                'std' => '25',
                'min_max_step' => [1, 100, 1],
                'section' => 'booking_options',
            ],

            [
                'id' => 'payment_heading',
                'label' => ilangs('Payment Gateways'),
                'type' => 'heading',
                'layout' => 'col-12 col-md-8',
                'std' => '',
                'section' => 'booking_options',
            ],

            [
                'id' => 'payment_tab',
                'label' => ilangs('Payment Tab'),
                'type' => 'tab',
                'layout' => 'col-12 col-md-8',
                'std' => '#e2a03f',
                'break' => true,
                'translation' => true,
                'tabs' => 'payment_settings',
                'section' => 'booking_options',
            ],


            //Car Service
            [
                'id' => 'service_tab',
                'label' => ilangs('Services'),
                'type' => 'tab',
                'layout' => 'col-12 col-md-8',
                'std' => '#e2a03f',
                'break' => true,
                'translation' => true,
                'tabs' => [
                    [
                        'id' => 'hotel_service',
                        'heading' => ilangs('Hotel'),
                        'fields' => [
                            [
                                'id' => 'hotel_link',
                                'label' => '',
                                'type' => 'link',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'std' => url('hotel'),
                                'tab' => 'hotel_service'
                            ],
                            [
                                'id' => 'hotel_enable',
                                'label' => ilangs('Enable'),
                                'type' => 'switcher',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'on',
                                'break' => true,
                                'tab' => 'hotel_service',
                            ],
                            [
                                'id' => 'hotel_approve',
                                'label' => ilangs('Need Approve to Publish'),
                                'type' => 'switcher',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'off',
                                'break' => true,
                                'tab' => 'hotel_service',
                                'condition' => 'hotel_enable:on'
                            ],
                            [
                                'id' => 'hotel_show_partner_info',
                                'label' => ilangs('Display owner info in detail page'),
                                'type' => 'switcher',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'on',
                                'break' => true,
                                'tab' => 'hotel_service',
                                'condition' => 'hotel_enable:on'
                            ],
                            [
                                'id' => 'hotel_search_radius',
                                'label' => ilangs('Search Radius'),
                                'type' => 'number',
                                'description' => ilangs('are calculated in kilometers from the searched location'),
                                'layout' => 'col-12 col-md-8',
                                'std' => '25',
                                'break' => true,
                                'min_max_step' => [1, 100, 1],
                                'tab' => 'hotel_service',
                                'condition' => 'hotel_enable:on'
                            ],
                            [
                                'id' => 'hotel_search_number',
                                'label' => ilangs('Search Number Items'),
                                'type' => 'number',
                                'layout' => 'col-12 col-md-8',
                                'std' => '6',
                                'break' => true,
                                'min_max_step' => [1, 50],
                                'tab' => 'hotel_service',
                                'condition' => 'hotel_enable:on'
                            ],
                            [
                                'id' => 'hotel_slider_text',
                                'label' => ilangs('Text on slider'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'Enjoy a great ride with ibooking',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'hotel_service',
                                'condition' => 'hotel_enable:on'
                            ],
                            [
                                'id' => 'hotel_slider',
                                'label' => ilangs('Slider'),
                                'type' => 'gallery',
                                'layout' => 'col-12 col-md-12',
                                'break' => true,
                                'tab' => 'hotel_service',
                                'condition' => 'hotel_enable:on'
                            ],
                            [
                                'id' => 'hotel_list_destination',
                                'type' => 'list_item',
                                'label' => ilangs('List Destinations'),
                                'translation' => true,
                                'binding' => 'name',
                                'fields' => [
                                    [
                                        'id' => 'name',
                                        'label' => ilangs('Name'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                        'translation' => true
                                    ],
                                    [
                                        'id' => 'image',
                                        'label' => ilangs('Feature Image'),
                                        'type' => 'image',
                                        'layout' => 'col-12',
                                        'break' => true,
                                    ],
                                    [
                                        'id' => 'lat',
                                        'label' => ilangs('Latitude'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                    ],
                                    [
                                        'id' => 'lng',
                                        'label' => ilangs('Longitude'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                    ]
                                ],
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'hotel_service',
                                'condition' => 'hotel_enable:on'
                            ],
                            [
                                'id' => 'hotel_testimonials',
                                'type' => 'list_item',
                                'label' => ilangs('Testimonial'),
                                'translation' => true,
                                'binding' => 'name',
                                'fields' => [
                                    [
                                        'id' => 'name',
                                        'label' => ilangs('Name'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                        'translation' => true
                                    ],
                                    [
                                        'id' => 'content',
                                        'label' => ilangs('Content'),
                                        'type' => 'textarea',
                                        'layout' => 'col-12',
                                        'translation' => true
                                    ],
                                ],
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'hotel_service',
                                'condition' => 'hotel_enable:on'
                            ],
                        ]
                    ],
                    [
                        'id' => 'apartment_service',
                        'heading' => ilangs('Apartment'),
                        'fields' => [
                            [
                                'id' => 'apartment_link',
                                'label' => '',
                                'type' => 'link',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'std' => url('apartment'),
                                'tab' => 'apartment_service'
                            ],
                            [
                                'id' => 'apartment_enable',
                                'label' => ilangs('Enable'),
                                'type' => 'switcher',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'on',
                                'break' => true,
                                'tab' => 'apartment_service',
                            ],
                            [
                                'id' => 'apartment_approve',
                                'label' => ilangs('Need Approve to Publish'),
                                'type' => 'switcher',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'off',
                                'break' => true,
                                'tab' => 'apartment_service',
                                'condition' => 'apartment_enable:on'
                            ],
                            [
                                'id' => 'apartment_show_partner_info',
                                'label' => ilangs('Display owner info in detail page'),
                                'type' => 'switcher',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'on',
                                'break' => true,
                                'tab' => 'apartment_service',
                                'condition' => 'apartment_enable:on'
                            ],
                            [
                                'id' => 'apartment_search_radius',
                                'label' => ilangs('Search Radius'),
                                'type' => 'number',
                                'description' => ilangs('are calculated in kilometers from the searched location'),
                                'layout' => 'col-12 col-md-8',
                                'std' => '25',
                                'break' => true,
                                'min_max_step' => [1, 100, 1],
                                'tab' => 'apartment_service',
                                'condition' => 'apartment_enable:on'
                            ],
                            [
                                'id' => 'apartment_search_number',
                                'label' => ilangs('Search Number Items'),
                                'type' => 'number',
                                'layout' => 'col-12 col-md-8',
                                'std' => '6',
                                'break' => true,
                                'min_max_step' => [1, 50],
                                'tab' => 'apartment_service',
                                'condition' => 'apartment_enable:on'
                            ],
                            [
                                'id' => 'apartment_slider_text',
                                'label' => ilangs('Text on slider'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'Enjoy a great ride with ibooking',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'apartment_service',
                                'condition' => 'apartment_enable:on'
                            ],
                            [
                                'id' => 'apartment_slider',
                                'label' => ilangs('Slider'),
                                'type' => 'gallery',
                                'layout' => 'col-12 col-md-12',
                                'break' => true,
                                'tab' => 'apartment_service',
                                'condition' => 'apartment_enable:on'
                            ],
                            [
                                'id' => 'apartment_list_destination',
                                'type' => 'list_item',
                                'label' => ilangs('List Destinations'),
                                'translation' => true,
                                'binding' => 'name',
                                'fields' => [
                                    [
                                        'id' => 'name',
                                        'label' => ilangs('Name'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                        'translation' => true
                                    ],
                                    [
                                        'id' => 'image',
                                        'label' => ilangs('Feature Image'),
                                        'type' => 'image',
                                        'layout' => 'col-12',
                                        'break' => true,
                                    ],
                                    [
                                        'id' => 'lat',
                                        'label' => ilangs('Latitude'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                    ],
                                    [
                                        'id' => 'lng',
                                        'label' => ilangs('Longitude'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                    ]
                                ],
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'apartment_service',
                                'condition' => 'apartment_enable:on'
                            ],
                            [
                                'id' => 'apartment_testimonials',
                                'type' => 'list_item',
                                'label' => ilangs('Testimonial'),
                                'translation' => true,
                                'binding' => 'name',
                                'fields' => [
                                    [
                                        'id' => 'name',
                                        'label' => ilangs('Name'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                        'translation' => true
                                    ],
                                    [
                                        'id' => 'content',
                                        'label' => ilangs('Content'),
                                        'type' => 'textarea',
                                        'layout' => 'col-12',
                                        'translation' => true
                                    ],
                                ],
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'apartment_service',
                                'condition' => 'apartment_enable:on'
                            ],
                        ]
                    ],
                    [
                        'id' => 'car_service',
                        'heading' => ilangs('Car'),
                        'fields' => [
                            [
                                'id' => 'car_link',
                                'label' => '',
                                'type' => 'link',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'std' => url('car'),
                                'tab' => 'car_service'
                            ],
                            [
                                'id' => 'car_enable',
                                'label' => ilangs('Enable'),
                                'type' => 'switcher',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'on',
                                'break' => true,
                                'tab' => 'car_service',
                            ],
                            [
                                'id' => 'car_approve',
                                'label' => ilangs('Need Approve to Publish'),
                                'type' => 'switcher',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'off',
                                'break' => true,
                                'condition' => 'car_enable:on',
                                'tab' => 'car_service',
                            ],
                            [
                                'id' => 'car_show_partner_info',
                                'label' => ilangs('Display owner info in detail page'),
                                'type' => 'switcher',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'on',
                                'break' => true,
                                'tab' => 'car_service',
                                'condition' => 'car_enable:on'
                            ],
                            [
                                'id' => 'car_search_radius',
                                'label' => ilangs('Search Radius'),
                                'type' => 'number',
                                'layout' => 'col-12 col-md-8',
                                'std' => '25',
                                'break' => true,
                                'min_max_step' => [1, 100, 1],
                                'tab' => 'car_service',
                                'condition' => 'car_enable:on'
                            ],
                            [
                                'id' => 'car_search_number',
                                'label' => ilangs('Search Number Items'),
                                'type' => 'number',
                                'layout' => 'col-12 col-md-8',
                                'std' => '6',
                                'break' => true,
                                'min_max_step' => [1, 50],
                                'tab' => 'car_service',
                                'condition' => 'car_enable:on'
                            ],
                            [
                                'id' => 'car_slider_text',
                                'label' => ilangs('Text on slider'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'Enjoy a great ride with ibooking',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'car_service',
                                'condition' => 'car_enable:on'
                            ],
                            [
                                'id' => 'car_slider',
                                'label' => ilangs('Slider'),
                                'type' => 'gallery',
                                'layout' => 'col-12 col-md-12',
                                'break' => true,
                                'tab' => 'car_service',
                                'condition' => 'car_enable:on'
                            ],
                            [
                                'id' => 'car_list_destination',
                                'type' => 'list_item',
                                'label' => ilangs('List Destinations'),
                                'translation' => true,
                                'binding' => 'name',
                                'fields' => [
                                    [
                                        'id' => 'name',
                                        'label' => ilangs('Name'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                        'translation' => true
                                    ],
                                    [
                                        'id' => 'image',
                                        'label' => ilangs('Feature Image'),
                                        'type' => 'image',
                                        'layout' => 'col-12',
                                        'break' => true,
                                    ],
                                    [
                                        'id' => 'lat',
                                        'label' => ilangs('Latitude'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                    ],
                                    [
                                        'id' => 'lng',
                                        'label' => ilangs('Longitude'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                    ]
                                ],
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'car_service',
                                'condition' => 'car_enable:on'
                            ],
                            [
                                'id' => 'car_testimonials',
                                'type' => 'list_item',
                                'label' => ilangs('Testimonial'),
                                'translation' => true,
                                'binding' => 'name',
                                'fields' => [
                                    [
                                        'id' => 'name',
                                        'label' => ilangs('Name'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                        'translation' => true
                                    ],
                                    [
                                        'id' => 'content',
                                        'label' => ilangs('Content'),
                                        'type' => 'textarea',
                                        'layout' => 'col-12',
                                        'translation' => true
                                    ],
                                ],
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'car_service',
                                'condition' => 'car_enable:on'
                            ],
                        ]
                    ],
                    [
                        'id' => 'space_service',
                        'heading' => ilangs('Space'),
                        'fields' => [
                            [
                                'id' => 'space_link',
                                'label' => '',
                                'type' => 'link',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'std' => url('space'),
                                'tab' => 'space_service'
                            ],
                            [
                                'id' => 'space_enable',
                                'label' => ilangs('Enable'),
                                'type' => 'switcher',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'on',
                                'break' => true,
                                'tab' => 'space_service',
                            ],
                            [
                                'id' => 'space_approve',
                                'label' => ilangs('Need Approve to Publish'),
                                'type' => 'switcher',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'off',
                                'break' => true,
                                'tab' => 'space_service',
                                'condition' => 'space_enable:on'
                            ],
                            [
                                'id' => 'space_show_partner_info',
                                'label' => ilangs('Display owner info in detail page'),
                                'type' => 'switcher',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'on',
                                'break' => true,
                                'tab' => 'space_service',
                                'condition' => 'space_enable:on'
                            ],
                            [
                                'id' => 'space_search_radius',
                                'label' => ilangs('Search Radius'),
                                'type' => 'number',
                                'description' => ilangs('are calculated in kilometers from the searched location'),
                                'layout' => 'col-12 col-md-8',
                                'std' => '25',
                                'break' => true,
                                'min_max_step' => [1, 100, 1],
                                'tab' => 'space_service',
                                'condition' => 'space_enable:on'
                            ],
                            [
                                'id' => 'space_search_number',
                                'label' => ilangs('Search Number Items'),
                                'type' => 'number',
                                'layout' => 'col-12 col-md-8',
                                'std' => '6',
                                'break' => true,
                                'min_max_step' => [1, 50],
                                'tab' => 'space_service',
                                'condition' => 'space_enable:on'
                            ],
                            [
                                'id' => 'space_slider_text',
                                'label' => ilangs('Text on slider'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'Enjoy a great ride with ibooking',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'space_service',
                                'condition' => 'space_enable:on'
                            ],
                            [
                                'id' => 'space_slider',
                                'label' => ilangs('Slider'),
                                'type' => 'gallery',
                                'layout' => 'col-12 col-md-12',
                                'break' => true,
                                'tab' => 'space_service',
                                'condition' => 'space_enable:on'
                            ],
                            [
                                'id' => 'space_list_destination',
                                'type' => 'list_item',
                                'label' => ilangs('List Destinations'),
                                'translation' => true,
                                'binding' => 'name',
                                'fields' => [
                                    [
                                        'id' => 'name',
                                        'label' => ilangs('Name'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                        'translation' => true
                                    ],
                                    [
                                        'id' => 'image',
                                        'label' => ilangs('Feature Image'),
                                        'type' => 'image',
                                        'layout' => 'col-12',
                                        'break' => true,
                                    ],
                                    [
                                        'id' => 'lat',
                                        'label' => ilangs('Latitude'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                    ],
                                    [
                                        'id' => 'lng',
                                        'label' => ilangs('Longitude'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                    ]
                                ],
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'space_service',
                                'condition' => 'space_enable:on'
                            ],
                            [
                                'id' => 'space_testimonials',
                                'type' => 'list_item',
                                'label' => ilangs('Testimonial'),
                                'translation' => true,
                                'binding' => 'name',
                                'fields' => [
                                    [
                                        'id' => 'name',
                                        'label' => ilangs('Name'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                        'translation' => true
                                    ],
                                    [
                                        'id' => 'content',
                                        'label' => ilangs('Content'),
                                        'type' => 'textarea',
                                        'layout' => 'col-12',
                                        'translation' => true
                                    ],
                                ],
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'space_service',
                                'condition' => 'space_enable:on'
                            ],
                        ]
                    ],
                    [
                        'id' => 'tour_service',
                        'heading' => ilangs('Tour'),
                        'fields' => [
                            [
                                'id' => 'tour_link',
                                'label' => '',
                                'type' => 'link',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'std' => url('tour'),
                                'tab' => 'tour_service'
                            ],
                            [
                                'id' => 'tour_enable',
                                'label' => ilangs('Enable'),
                                'type' => 'switcher',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'on',
                                'break' => true,
                                'tab' => 'tour_service',
                            ],
                            [
                                'id' => 'tour_approve',
                                'label' => ilangs('Need Approve to Publish'),
                                'type' => 'switcher',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'off',
                                'break' => true,
                                'tab' => 'tour_service',
                                'condition' => 'tour_enable:on'
                            ],
                            [
                                'id' => 'tour_show_partner_info',
                                'label' => ilangs('Display owner info in detail page'),
                                'type' => 'switcher',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'on',
                                'break' => true,
                                'tab' => 'tour_service',
                                'condition' => 'tour_enable:on'
                            ],
                            [
                                'id' => 'tour_search_radius',
                                'label' => ilangs('Search Radius'),
                                'type' => 'number',
                                'description' => ilangs('are calculated in kilometers from the searched location'),
                                'layout' => 'col-12 col-md-8',
                                'std' => '25',
                                'break' => true,
                                'min_max_step' => [1, 100, 1],
                                'tab' => 'tour_service',
                                'condition' => 'tour_enable:on'
                            ],
                            [
                                'id' => 'tour_search_number',
                                'label' => ilangs('Search Number Items'),
                                'type' => 'number',
                                'layout' => 'col-12 col-md-8',
                                'std' => '6',
                                'break' => true,
                                'min_max_step' => [1, 50],
                                'tab' => 'tour_service',
                                'condition' => 'tour_enable:on'
                            ],
                            [
                                'id' => 'tour_slider_text',
                                'label' => ilangs('Text on slider'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'Enjoy a great ride with ibooking',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'tour_service',
                                'condition' => 'tour_enable:on'
                            ],
                            [
                                'id' => 'tour_slider',
                                'label' => ilangs('Slider'),
                                'type' => 'gallery',
                                'layout' => 'col-12 col-md-12',
                                'break' => true,
                                'tab' => 'tour_service',
                                'condition' => 'tour_enable:on'
                            ],
                            [
                                'id' => 'tour_list_destination',
                                'type' => 'list_item',
                                'label' => ilangs('List Destinations'),
                                'translation' => true,
                                'binding' => 'name',
                                'fields' => [
                                    [
                                        'id' => 'name',
                                        'label' => ilangs('Name'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                        'translation' => true
                                    ],
                                    [
                                        'id' => 'image',
                                        'label' => ilangs('Feature Image'),
                                        'type' => 'image',
                                        'layout' => 'col-12',
                                        'break' => true,
                                    ],
                                    [
                                        'id' => 'lat',
                                        'label' => ilangs('Latitude'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                    ],
                                    [
                                        'id' => 'lng',
                                        'label' => ilangs('Longitude'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                    ]
                                ],
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'tour_service',
                                'condition' => 'tour_enable:on'
                            ],
                            [
                                'id' => 'tour_testimonials',
                                'type' => 'list_item',
                                'label' => ilangs('Testimonial'),
                                'translation' => true,
                                'binding' => 'name',
                                'fields' => [
                                    [
                                        'id' => 'name',
                                        'label' => ilangs('Name'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                        'translation' => true
                                    ],
                                    [
                                        'id' => 'content',
                                        'label' => ilangs('Content'),
                                        'type' => 'textarea',
                                        'layout' => 'col-12',
                                        'translation' => true
                                    ],
                                ],
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'tour_service',
                                'condition' => 'tour_enable:on'
                            ],
                        ]
                    ],
                    [
                        'id' => 'beauty_services',
                        'heading' => ilangs('Beauty'),
                        'fields' => [
                            [
                                'id' => 'beauty_link',
                                'label' => '',
                                'type' => 'link',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'std' => url('beauty-services'),
                                'tab' => 'beauty_services'
                            ],
                            [
                                'id' => 'beauty_enable',
                                'label' => ilangs('Enable'),
                                'type' => 'switcher',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'on',
                                'break' => true,
                                'tab' => 'beauty_services',
                            ],
                            [
                                'id' => 'beauty_approve',
                                'label' => ilangs('Need Approve to Publish'),
                                'type' => 'switcher',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'off',
                                'break' => true,
                                'tab' => 'beauty_services',
                                'condition' => 'beauty_enable:on'
                            ],
                            [
                                'id' => 'beauty_show_partner_info',
                                'label' => ilangs('Display owner info in detail page'),
                                'type' => 'switcher',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'on',
                                'break' => true,
                                'tab' => 'beauty_service',
                                'condition' => 'beauty_enable:on'
                            ],
                            [
                                'id' => 'beauty_search_radius',
                                'label' => ilangs('Search Radius'),
                                'type' => 'number',
                                'description' => ilangs('are calculated in kilometers from the searched location'),
                                'layout' => 'col-12 col-md-8',
                                'std' => '25',
                                'break' => true,
                                'min_max_step' => [1, 100, 1],
                                'tab' => 'beauty_services',
                                'condition' => 'beauty_enable:on'
                            ],
                            [
                                'id' => 'beauty_search_number',
                                'label' => ilangs('Search Number Items'),
                                'type' => 'number',
                                'layout' => 'col-12 col-md-8',
                                'std' => '6',
                                'break' => true,
                                'min_max_step' => [1, 50],
                                'tab' => 'beauty_services',
                                'condition' => 'beauty_enable:on'
                            ],
                            [
                                'id' => 'beauty_slider_text',
                                'label' => ilangs('Text on slider'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'std' => 'Enjoy a great ride with ibooking',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'beauty_services',
                                'condition' => 'beauty_enable:on'
                            ],
                            [
                                'id' => 'beauty_slider',
                                'label' => ilangs('Slider'),
                                'type' => 'gallery',
                                'layout' => 'col-12 col-md-12',
                                'break' => true,
                                'tab' => 'beauty_services',
                                'condition' => 'beauty_enable:on'
                            ],
                            [
                                'id' => 'beauty_list_destination',
                                'type' => 'list_item',
                                'label' => ilangs('List Destinations'),
                                'translation' => true,
                                'binding' => 'name',
                                'fields' => [
                                    [
                                        'id' => 'name',
                                        'label' => ilangs('Name'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                        'translation' => true
                                    ],
                                    [
                                        'id' => 'image',
                                        'label' => ilangs('Feature Image'),
                                        'type' => 'image',
                                        'layout' => 'col-12',
                                        'break' => true,
                                    ],
                                    [
                                        'id' => 'lat',
                                        'label' => ilangs('Latitude'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                    ],
                                    [
                                        'id' => 'lng',
                                        'label' => ilangs('Longitude'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                    ]
                                ],
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'beauty_services',
                                'condition' => 'beauty_enable:on'
                            ],
                            [
                                'id' => 'beauty_testimonials',
                                'type' => 'list_item',
                                'label' => ilangs('Testimonial'),
                                'translation' => true,
                                'binding' => 'name',
                                'fields' => [
                                    [
                                        'id' => 'name',
                                        'label' => ilangs('Name'),
                                        'type' => 'text',
                                        'layout' => 'col-12',
                                        'translation' => true
                                    ],
                                    [
                                        'id' => 'content',
                                        'label' => ilangs('Content'),
                                        'type' => 'textarea',
                                        'layout' => 'col-12',
                                        'translation' => true
                                    ],
                                ],
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'beauty_services',
                                'condition' => 'beauty_enable:on'
                            ],
                        ]
                    ],
                ],
                'section' => 'service_options',
            ],

            //Review
            [
                'id' => 'enable_post_review',
                'label' => ilangs('Enable Post Review'),
                'type' => 'switcher',
                'layout' => 'col-12 col-md-8',
                'std' => 'on',
                'section' => 'review_options'
            ],
            [
                'id' => 'enable_review',
                'label' => ilangs('Enable Service Review'),
                'type' => 'switcher',
                'layout' => 'col-12 col-md-8',
                'std' => 'on',
                'section' => 'review_options'
            ],
            [
                'id' => 'need_booking_to_review',
                'label' => ilangs('Need Booking To Review'),
                'type' => 'switcher',
                'layout' => 'col-12 col-md-8',
                'std' => 'off',
                'section' => 'review_options'
            ],
            [
                'id' => 'need_approve_review',
                'label' => ilangs('Need Approve To Publish Review'),
                'type' => 'switcher',
                'layout' => 'col-12 col-md-8',
                'std' => 'off',
                'section' => 'review_options'
            ],
            //Appearance
            [
                'id' => 'appearance_tab',
                'label' => ilangs('Appearance'),
                'type' => 'tab',
                'layout' => 'col-12 col-md-8',
                'std' => '#e2a03f',
                'break' => true,
                'translation' => true,
                'section' => 'appearance_options',
                'tabs' => [
                    [
                        'id' => 'footer',
                        'heading' => ilangs('Footer'),
                        'fields' => [
                            //Footer
                            [
                                'id' => 'footer_menu_1_heading',
                                'label' => ilangs('First Widget Heading'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'footer'
                            ],
                            [
                                'id' => 'footer_menu_1',
                                'label' => ilangs('First Widget'),
                                'type' => 'select',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'choices' => 'menu',
                                'tab' => 'footer'
                            ],
                            [
                                'id' => 'footer_menu_2_heading',
                                'label' => ilangs('Second Widget Heading'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'footer'
                            ],
                            [
                                'id' => 'footer_menu_2',
                                'label' => ilangs('Second Widget'),
                                'type' => 'select',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'choices' => 'menu',
                                'tab' => 'footer'
                            ],
                            [
                                'id' => 'footer_menu_3_heading',
                                'label' => ilangs('Third Widget Heading'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'footer'
                            ],
                            [
                                'id' => 'footer_menu_3',
                                'label' => ilangs('Third Widget'),
                                'type' => 'select',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'choices' => 'menu',
                                'tab' => 'footer'
                            ],
                            [
                                'id' => 'footer_menu_4',
                                'label' => ilangs('Footer Menu'),
                                'type' => 'select',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'choices' => 'menu',
                                'tab' => 'footer'
                            ],
                            [
                                'id' => 'footer_copyright',
                                'label' => ilangs('Copyright'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'footer'
                            ],
                            [
                                'id' => 'social',
                                'type' => 'list_item',
                                'label' => ilangs('Social'),
                                'translation' => true,
                                'binding' => 'title',
                                'fields' => [
                                    [
                                        'id' => 'title',
                                        'label' => ilangs('Title'),
                                        'type' => 'text',
                                        'layout' => 'col-12 col-md-6',
                                        'translation' => true
                                    ],
                                    [
                                        'id' => 'icon',
                                        'label' => ilangs('Icon'),
                                        'type' => 'icon_picker',
                                        'layout' => 'col-12 col-md-6',
                                        'break' => true,
                                    ],
                                    [
                                        'id' => 'url',
                                        'label' => ilangs('Url'),
                                        'type' => 'text',
                                        'layout' => 'col-12 col-md-12',
                                        'break' => true,
                                    ],
                                ],
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'footer'
                            ],
                        ]
                    ],
                    [
                        'id' => 'top_bar',
                        'heading' => ilangs('Top bar'),
                        'fields' => [
                            [
                                'id' => 'top_bar_display',
                                'label' => ilangs('Display'),
                                'type' => 'switcher',
                                'std' => 'on',
                                'tab' => 'top_bar'
                            ],
                            [
                                'id' => 'top_bar_promo_text',
                                'label' => ilangs('Promo Title'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'top_bar'
                            ],
                            [
                                'id' => 'top_bar_promo_code',
                                'label' => ilangs('Coupon Code'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'top_bar'
                            ],
                            [
                                'id' => 'top_bar_button_text',
                                'label' => ilangs('Button Text'),
                                'type' => 'text',
                                'std' => ilangs('SEE NOW'),
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'top_bar'
                            ],
                            [
                                'id' => 'top_bar_button_url',
                                'label' => ilangs('Button Url'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'tab' => 'top_bar'
                            ],
                        ],
                    ],
                    [
                        'id' => 'gdpr_cookies',
                        'heading' => ilangs('GDPR Cookies'),
                        'fields' => [
                            [
                                'id' => 'gdpr_enable',
                                'label' => ilangs('Enable GDPR Cookies'),
                                'type' => 'switcher',
                                'std' => 'off',
                                'tab' => 'gdpr_cookies'
                            ],
                            [
                                'id' => 'gdpr_hide_after_click',
                                'label' => ilangs('Hide After Click'),
                                'type' => 'switcher',
                                'std' => 'on',
                                'tab' => 'gdpr_cookies'
                            ],
                            [
                                'id' => 'gdpr_position',
                                'label' => ilangs('Position'),
                                'type' => 'select',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'std' => 'left',
                                'choices' => [
                                    'left' => ilangs('Left'),
                                    'right' => ilangs('Right'),
                                ],
                                'tab' => 'gdpr_cookies'
                            ],
                            [
                                'id' => 'gdpr_manage_text',
                                'label' => ilangs('Manage Text'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'gdpr_cookies'
                            ],
                            [
                                'id' => 'gdpr_banner_heading',
                                'label' => ilangs('Banner Heading'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'gdpr_cookies'
                            ],
                            [
                                'id' => 'gdpr_banner_description',
                                'label' => ilangs('Banner Description'),
                                'type' => 'textarea',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'gdpr_cookies'
                            ],
                            [
                                'id' => 'gdpr_banner_link_text',
                                'label' => ilangs('Banner Link Text'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'gdpr_cookies'
                            ],
                            [
                                'id' => 'gdpr_policy_page',
                                'label' => ilangs('Policy Page'),
                                'type' => 'select',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'std' => 'left',
                                'choices' => 'page',
                                'tab' => 'gdpr_cookies'
                            ],
                            [
                                'id' => 'gdpr_button_accept_text',
                                'label' => ilangs('Button Accept Text'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'gdpr_cookies'
                            ],
                            [
                                'id' => 'gdpr_button_reject_text',
                                'label' => ilangs('Button Reject Text'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-8',
                                'break' => true,
                                'translation' => true,
                                'tab' => 'gdpr_cookies'
                            ],
                        ],
                    ],
                ],
            ],

            //Email
            [
                'id' => 'logo_email',
                'label' => ilangs('Logo Email'),
                'type' => 'image',
                'layout' => 'col-12 col-md-8',
                'break' => true,
                'section' => 'email_options'
            ],
            [
                'id' => 'enable_queue_mail',
                'label' => ilangs('Enable Queue Mail'),
                'type' => 'switcher',
                'std' => 'off',
                'break' => true,
                'section' => 'email_options'
            ],
            [
                'id' => 'email_heading',
                'label' => ilangs('Config Email'),
                'type' => 'heading',
                'layout' => 'col-12 col-md-8',
                'break' => true,
                'section' => 'email_options'
            ],
            [
                'id' => 'email_host',
                'label' => ilangs('Host'),
                'type' => 'text',
                'layout' => 'col-12 col-md-8',
                'break' => true,
                'section' => 'email_options'
            ],
            [
                'id' => 'email_username',
                'label' => ilangs('Username'),
                'type' => 'text',
                'layout' => 'col-12 col-md-8',
                'break' => true,
                'section' => 'email_options'
            ],
            [
                'id' => 'email_password',
                'label' => ilangs('Password'),
                'type' => 'text',
                'layout' => 'col-12 col-md-8',
                'break' => true,
                'section' => 'email_options'
            ],
            [
                'id' => 'email_port',
                'label' => ilangs('Port'),
                'type' => 'text',
                'layout' => 'col-12 col-md-8',
                'break' => true,
                'section' => 'email_options'
            ],
            [
                'id' => 'email_encryption',
                'label' => ilangs('Encryption'),
                'type' => 'text',
                'layout' => 'col-12 col-md-8',
                'break' => true,
                'section' => 'email_options'
            ],

            //Invoice
            [
                'id' => 'invoice_logo',
                'label' => ilangs('Invoice Logo'),
                'type' => 'image',
                'layout' => 'col-12 col-md-8',
                'break' => true,
                'section' => 'invoice_options'
            ],
            [
                'id' => 'invoice_name',
                'label' => ilangs('Company Name'),
                'type' => 'text',
                'layout' => 'col-12 col-md-8',
                'break' => true,
                'section' => 'invoice_options'
            ],
            [
                'id' => 'invoice_address',
                'label' => ilangs('Address'),
                'type' => 'text',
                'layout' => 'col-12 col-md-8',
                'break' => true,
                'section' => 'invoice_options'
            ],

            //Advanced
            [
                'id' => 'page_term_conditional',
                'label' => ilangs('Terms and Conditions Page'),
                'type' => 'select',
                'layout' => 'col-12 col-md-8',
                'break' => true,
                'choices' => 'page',
                'section' => 'advanced_options'
            ],
            [
                'id' => 'site_language',
                'label' => ilangs('Site Language'),
                'type' => 'select',
                'layout' => 'col-12 col-md-8',
                'break' => true,
                'choices' => 'language',
                'section' => 'advanced_options'
            ],
            [
                'id' => 'multi_language',
                'label' => ilangs('Multi Language'),
                'type' => 'switcher',
                'std' => 'off',
                'break' => true,
                'section' => 'advanced_options'
            ],
            [
                'id' => 'is_rtl',
                'label' => ilangs('Is RTL Layout'),
                'type' => 'switcher',
                'std' => 'off',
                'break' => true,
                'section' => 'advanced_options'
            ],
            [
                'id' => 'date_format',
                'label' => ilangs('Date Format'),
                'type' => 'text',
                'layout' => 'col-12 col-md-8',
                'break' => true,
                'std' => 'd/m/Y',
                'section' => 'advanced_options'
            ],
            [
                'id' => 'time_format',
                'label' => ilangs('Time Format'),
                'type' => 'text',
                'layout' => 'col-12 col-md-8',
                'break' => true,
                'std' => 'h:i A',
                'section' => 'advanced_options'
            ],
            [
                'id' => 'time_type',
                'label' => ilangs('Time Type'),
                'type' => 'select',
                'layout' => 'col-12 col-md-8',
                'break' => true,
                'choices' => [
                    '12' => ilangs('12h'),
                    '24' => ilangs('24h'),
                ],
                'section' => 'advanced_options'
            ],
            [
                'id' => 'unit_of_measure',
                'label' => ilangs('Unit Of Measure'),
                'type' => 'select',
                'layout' => 'col-12 col-md-8',
                'break' => true,
                'std' => 'm2',
                'choices' => [
                    'm2' => ilangs('m2'),
                    'ft2' => ilangs('ft2'),
                ],
                'section' => 'advanced_options'
            ],

            [
                'id' => 'mapbox_token',
                'label' => ilangs('Mapbox Token'),
                'type' => 'textarea',
                'layout' => 'col-12',
                'break' => true,
                'section' => 'advanced_options'
            ],

            //Social Network
            [
                'id' => 'social_heading',
                'label' => ilangs('Social Login'),
                'type' => 'heading',
                'layout' => 'col-12 col-md-8',
                'std' => '',
                'section' => 'advanced_options',
            ],
            [
                'id' => 'social_tab',
                'label' => ilangs('Social Network'),
                'type' => 'tab',
                'layout' => 'col-12 col-md-8',
                'std' => '#e2a03f',
                'break' => true,
                'translation' => true,
                'tabs' => [
                    [
                        'id' => 'facebook_login',
                        'heading' => ilangs('Facebook Login'),
                        'fields' => [
                            [
                                'id' => 'facebook_login_enable',
                                'label' => ilangs('Enable'),
                                'type' => 'switcher',
                                'layout' => 'col-12',
                                'std' => 'on',
                                'break' => true,
                                'tab' => 'facebook_login'
                            ],
                            [
                                'id' => 'facebook_login_client_id',
                                'label' => ilangs('App ID'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-6',
                                'condition' => 'facebook_login_enable:on',
                                'tab' => 'facebook_login',
                            ],
                            [
                                'id' => 'facebook_login_client_secret',
                                'label' => ilangs('App Secret'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-6',
                                'break' => true,
                                'condition' => 'facebook_login_enable:on',
                                'tab' => 'facebook_login'
                            ],
                            [
                                'id' => 'facebook_login_redirect_url',
                                'label' => ilangs('Callback Url'),
                                'type' => 'text',
                                'layout' => 'col-12',
                                'break' => true,
                                'condition' => 'facebook_login_enable:on',
                                'tab' => 'facebook_login'
                            ]
                        ]
                    ],
                    [
                        'id' => 'google_login',
                        'heading' => ilangs('Google Login'),
                        'fields' => [
                            [
                                'id' => 'google_login_enable',
                                'label' => ilangs('Enable'),
                                'type' => 'switcher',
                                'layout' => 'col-12',
                                'std' => 'on',
                                'break' => true,
                                'tab' => 'google_login'
                            ],
                            [
                                'id' => 'google_login_client_id',
                                'label' => ilangs('Client ID'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-6',
                                'condition' => 'google_login_enable:on',
                                'tab' => 'google_login',
                            ],
                            [
                                'id' => 'google_login_client_secret',
                                'label' => ilangs('Client Secret'),
                                'type' => 'text',
                                'layout' => 'col-12 col-md-6',
                                'break' => true,
                                'condition' => 'google_login_enable:on',
                                'tab' => 'google_login'
                            ],
                            [
                                'id' => 'google_login_redirect_url',
                                'label' => ilangs('Redirect Url'),
                                'type' => 'text',
                                'layout' => 'col-12',
                                'break' => true,
                                'condition' => 'google_login_enable:on',
                                'tab' => 'google_login'
                            ]
                        ]
                    ]
                ],
                'section' => 'advanced_options',
            ],
        ]
    ]
];