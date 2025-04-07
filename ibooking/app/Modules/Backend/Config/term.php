<?php
return [
	'post-category' => [
		[
			'id' => 'term_title',
			'label' => ilangs('Title'),
			'type' => 'text',
			'std' => '',
            'validation' => 'required',
			'break' => true,
			'translation' => true,
		],
        [
            'id' => 'parent',
            'label' => ilangs('Parent'),
            'type' => 'select',
            'std' => '',
            'break' => true,
            'choices' => 'term:name:post-category:true'
        ],
		[
			'id' => 'term_description',
			'label' => ilangs('Description'),
			'type' => 'textarea',
			'rows' => 5,
			'break' => true,
			'translation' => true,
            'breakpoints' => 'xs sm'
		],
	],
    'post-tag' => [
        [
            'id' => 'term_title',
            'label' => ilangs('Title'),
            'type' => 'text',
            'std' => '',
            'validation' => 'required',
            'break' => true,
            'translation' => true,
        ],
        [
            'id' => 'term_description',
            'label' => ilangs('Description'),
            'type' => 'textarea',
            'rows' => 5,
            'break' => true,
            'translation' => true,
            'breakpoints' => 'xs sm'
        ]
    ],
    'car-type' => [
        [
            'id' => 'term_title',
            'label' => ilangs('Title'),
            'type' => 'text',
            'std' => '',
            'validation' => 'required',
            'break' => true,
            'translation' => true,
        ],
	    [
		    'id' => 'parent',
		    'label' => ilangs('Parent'),
		    'type' => 'select',
		    'std' => '',
		    'break' => true,
		    'choices' => 'term:name:car-type:true'
	    ],
        [
            'id' => 'term_image',
            'label' => ilangs('Image'),
            'type' => 'image',
            'layout' => 'col-12 col-md-6',
            'break' => true,
            'breakpoints' => 'xs sm'
        ],
        [
            'id' => 'term_description',
            'label' => ilangs('Description'),
            'type' => 'textarea',
            'rows' => 5,
            'break' => true,
            'translation' => true,
            'breakpoints' => 'xs sm'
        ]
    ],
    'car-feature' => [
        [
            'id' => 'term_title',
            'label' => ilangs('Title'),
            'type' => 'text',
            'std' => '',
            'validation' => 'required',
            'break' => true,
            'translation' => true,
        ],
        [
            'id' => 'term_icon',
            'label' => ilangs('Icon'),
            'type' => 'icon_picker',
            'layout' => 'col-12',
            'break' => true,
            'breakpoints' => 'xs sm'
        ],
        [
            'id' => 'term_description',
            'label' => ilangs('Description'),
            'type' => 'textarea',
            'rows' => 5,
            'break' => true,
            'translation' => true,
            'breakpoints' => 'xs sm'
        ]
    ],
    'car-equipment' => [
        [
            'id' => 'term_title',
            'label' => ilangs('Title'),
            'type' => 'text',
            'std' => '',
            'validation' => 'required',
            'break' => true,
            'translation' => true,
        ],
        [
            'id' => 'term_price',
            'label' => ilangs('Price'),
            'type' => 'text',
            'std' => '',
            'validation' => 'required',
            'break' => true,
            'breakpoints' => 'xs sm'
        ],
        [
            'id' => 'term_icon',
            'label' => ilangs('Icon'),
            'type' => 'icon_picker',
            'layout' => 'col-12',
            'break' => true,
            'breakpoints' => 'xs sm'
        ],
        [
            'id' => 'term_description',
            'label' => ilangs('Description'),
            'type' => 'textarea',
            'rows' => 5,
            'break' => true,
            'translation' => true,
            'breakpoints' => 'xs sm'
        ]
    ],
    'apartment-type' => [
        [
            'id' => 'term_title',
            'label' => ilangs('Title'),
            'type' => 'text',
            'std' => '',
            'validation' => 'required',
            'break' => true,
            'translation' => true,
        ],
	    [
		    'id' => 'parent',
		    'label' => ilangs('Parent'),
		    'type' => 'select',
		    'std' => '',
		    'break' => true,
		    'choices' => 'term:name:apartment-type:true'
	    ],
        [
            'id' => 'term_image',
            'label' => ilangs('Image'),
            'type' => 'image',
            'layout' => 'col-12 col-md-6',
            'break' => true,
            'breakpoints' => 'xs sm'
        ],
        [
            'id' => 'term_description',
            'label' => ilangs('Description'),
            'type' => 'textarea',
            'rows' => 5,
            'break' => true,
            'translation' => true,
            'breakpoints' => 'xs sm'
        ]
    ],
    'apartment-amenity' => [
        [
            'id' => 'term_title',
            'label' => ilangs('Title'),
            'type' => 'text',
            'std' => '',
            'validation' => 'required',
            'break' => true,
            'translation' => true,
        ],
        [
            'id' => 'term_icon',
            'label' => ilangs('Icon'),
            'type' => 'icon_picker',
            'layout' => 'col-12',
            'break' => true,
            'breakpoints' => 'xs'
        ],
        [
            'id' => 'term_description',
            'label' => ilangs('Description'),
            'type' => 'textarea',
            'rows' => 5,
            'break' => true,
            'translation' => true,
            'breakpoints' => 'xs sm'
        ]
    ],
    'property-type' => [
        [
            'id' => 'term_title',
            'label' => ilangs('Title'),
            'type' => 'text',
            'std' => '',
            'validation' => 'required',
            'break' => true,
            'translation' => true,
        ],
        [
            'id' => 'parent',
            'label' => ilangs('Parent'),
            'type' => 'select',
            'std' => '',
            'break' => true,
            'choices' => 'term:name:property-type:true'
        ],
        [
            'id' => 'term_image',
            'label' => ilangs('Image'),
            'type' => 'image',
            'layout' => 'col-12 col-md-6',
            'break' => true,
            'breakpoints' => 'xs sm'
        ],
        [
            'id' => 'term_description',
            'label' => ilangs('Description'),
            'type' => 'textarea',
            'rows' => 5,
            'break' => true,
            'translation' => true,
            'breakpoints' => 'xs sm'
        ]
    ],
    'hotel-facilities' => [
        [
            'id' => 'term_title',
            'label' => ilangs('Title'),
            'type' => 'text',
            'std' => '',
            'validation' => 'required',
            'break' => true,
            'translation' => true,
        ],
        [
            'id' => 'term_icon',
            'label' => ilangs('Icon'),
            'type' => 'icon_picker',
            'layout' => 'col-12',
            'break' => true,
            'breakpoints' => 'xs'
        ],
        [
            'id' => 'term_description',
            'label' => ilangs('Description'),
            'type' => 'textarea',
            'rows' => 5,
            'break' => true,
            'translation' => true,
            'breakpoints' => 'xs sm'
        ]
    ],
    'hotel-services' => [
        [
            'id' => 'term_title',
            'label' => ilangs('Title'),
            'type' => 'text',
            'std' => '',
            'validation' => 'required',
            'break' => true,
            'translation' => true,
        ],
        [
            'id' => 'term_icon',
            'label' => ilangs('Icon'),
            'type' => 'icon_picker',
            'layout' => 'col-12',
            'break' => true,
            'breakpoints' => 'xs'
        ],
        [
            'id' => 'term_description',
            'label' => ilangs('Description'),
            'type' => 'textarea',
            'rows' => 5,
            'break' => true,
            'translation' => true,
            'breakpoints' => 'xs sm'
        ]
    ],
    'room-facilities' => [
        [
            'id' => 'term_title',
            'label' => ilangs('Title'),
            'type' => 'text',
            'std' => '',
            'validation' => 'required',
            'break' => true,
            'translation' => true,
        ],
        [
            'id' => 'term_icon',
            'label' => ilangs('Icon'),
            'type' => 'icon_picker',
            'layout' => 'col-12',
            'break' => true,
            'breakpoints' => 'xs'
        ],
        [
            'id' => 'term_description',
            'label' => ilangs('Description'),
            'type' => 'textarea',
            'rows' => 5,
            'break' => true,
            'translation' => true,
            'breakpoints' => 'xs sm'
        ]
    ],
   'beauty-services' => [
      [
         'id' => 'term_title',
         'label' => ilangs('Title'),
         'type' => 'text',
         'std' => '',
         'validation' => 'required',
         'break' => true,
         'translation' => true,
      ],
       [
           'id' => 'term_image',
           'label' => ilangs('Image'),
           'type' => 'image',
           'layout' => 'col-12 col-md-6',
           'break' => true,
           'breakpoints' => 'xs sm'
       ],
      [
         'id' => 'term_description',
         'label' => ilangs('Description'),
         'type' => 'textarea',
         'rows' => 5,
         'break' => true,
         'translation' => true,
         'breakpoints' => 'xs sm'
      ]
   ],
    'beauty-branch' => [
        [
            'id' => 'term_title',
            'label' => ilangs('Branch Name'),
            'type' => 'text',
            'std' => '',
            'validation' => 'required',
            'break' => true,
            'translation' => true,
        ],
        [
            'id' => 'term_description',
            'label' => ilangs('Address'),
            'type' => 'textarea',
            'rows' => 5,
            'break' => true,
            'translation' => true,
            'breakpoints' => 'xs sm'
        ],
        [
            'id' => 'term_location',
            'label' => ilangs('Location'),
            'type' => 'location',
            'break' => true,
            'breakpoints' => 'xs sm',
            'hide_real_address' => true
        ]
    ],
	'space-type' => [
		[
			'id' => 'term_title',
			'label' => ilangs('Title'),
			'type' => 'text',
			'std' => '',
			'validation' => 'required',
			'break' => true,
			'translation' => true,
		],
		[
			'id' => 'parent',
			'label' => ilangs('Parent'),
			'type' => 'select',
			'std' => '',
			'break' => true,
			'choices' => 'term:name:space-type:true'
		],
		[
			'id' => 'term_image',
			'label' => ilangs('Image'),
			'type' => 'image',
			'layout' => 'col-12 col-md-6',
			'break' => true,
			'breakpoints' => 'xs sm'
		],
		[
			'id' => 'term_description',
			'label' => ilangs('Description'),
			'type' => 'textarea',
			'rows' => 5,
			'break' => true,
			'translation' => true,
			'breakpoints' => 'xs sm'
		]
	],
	'space-amenity' => [
		[
			'id' => 'term_title',
			'label' => ilangs('Title'),
			'type' => 'text',
			'std' => '',
			'validation' => 'required',
			'break' => true,
			'translation' => true,
		],
		[
			'id' => 'term_icon',
			'label' => ilangs('Icon'),
			'type' => 'icon_picker',
			'layout' => 'col-12',
			'break' => true,
			'breakpoints' => 'xs'
		],
		[
			'id' => 'term_description',
			'label' => ilangs('Description'),
			'type' => 'textarea',
			'rows' => 5,
			'break' => true,
			'translation' => true,
			'breakpoints' => 'xs sm'
		]
	],
    'tour-type' => [
        [
            'id' => 'term_title',
            'label' => ilangs('Title'),
            'type' => 'text',
            'std' => '',
            'validation' => 'required',
            'break' => true,
            'translation' => true,
        ],
        [
            'id' => 'parent',
            'label' => ilangs('Parent'),
            'type' => 'select',
            'std' => '',
            'break' => true,
            'choices' => 'term:name:tour-type:true'
        ],
        [
            'id' => 'term_image',
            'label' => ilangs('Image'),
            'type' => 'image',
            'layout' => 'col-12 col-md-6',
            'break' => true,
            'breakpoints' => 'xs sm'
        ],
        [
            'id' => 'term_description',
            'label' => ilangs('Description'),
            'type' => 'textarea',
            'rows' => 5,
            'break' => true,
            'translation' => true,
            'breakpoints' => 'xs sm'
        ]
    ],
    'tour-include' => [
        [
            'id' => 'term_title',
            'label' => ilangs('Title'),
            'type' => 'text',
            'std' => '',
            'validation' => 'required',
            'break' => true,
            'translation' => true,
        ],
        [
            'id' => 'term_description',
            'label' => ilangs('Description'),
            'type' => 'textarea',
            'rows' => 5,
            'break' => true,
            'translation' => true,
            'breakpoints' => 'xs sm'
        ]
    ],
    'tour-exclude' => [
        [
            'id' => 'term_title',
            'label' => ilangs('Title'),
            'type' => 'text',
            'std' => '',
            'validation' => 'required',
            'break' => true,
            'translation' => true,
        ],
        [
            'id' => 'term_description',
            'label' => ilangs('Description'),
            'type' => 'textarea',
            'rows' => 5,
            'break' => true,
            'translation' => true,
            'breakpoints' => 'xs sm'
        ]
    ],
];