<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxonomySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $repo = \App\Repositories\TaxonomyRepository::inst();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $repo->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $data = [
            [
                'taxonomy_title' => 'Categories',
                'taxonomy_name' => 'post-category',
                'post_type' => 'post'
            ],
            [
                'taxonomy_title' => 'Tags',
                'taxonomy_name' => 'post-tag',
                'post_type' => 'post'
            ],
            [
                'taxonomy_title' => 'Car Types',
                'taxonomy_name' => 'car-type',
                'post_type' => 'car'
            ],
            [
                'taxonomy_title' => 'Car Features',
                'taxonomy_name' => 'car-feature',
                'post_type' => 'car'
            ],
            [
                'taxonomy_title' => 'Car Equipments',
                'taxonomy_name' => 'car-equipment',
                'post_type' => 'car'
            ],
            [
                'taxonomy_title' => 'Apartment Types',
                'taxonomy_name' => 'apartment-type',
                'post_type' => 'apartment'
            ],
            [
                'taxonomy_title' => 'Apartment Amenities',
                'taxonomy_name' => 'apartment-amenity',
                'post_type' => 'apartment'
            ],
            [
                'taxonomy_title' => 'Property Types',
                'taxonomy_name' => 'property-type',
                'post_type' => 'hotel'
            ],
            [
                'taxonomy_title' => 'Hotel Facilities',
                'taxonomy_name' => 'hotel-facilities',
                'post_type' => 'hotel'
            ],
            [
                'taxonomy_title' => 'Hotel Services',
                'taxonomy_name' => 'hotel-services',
                'post_type' => 'hotel'
            ],
            [
                'taxonomy_title' => 'Room Facilities',
                'taxonomy_name' => 'room-facilities',
                'post_type' => 'room'
            ],
            [
                'taxonomy_title' => 'Service Categories',
                'taxonomy_name' => 'beauty-services',
                'post_type' => 'beauty'
            ],
            [
                'taxonomy_title' => 'Beauty Branch',
                'taxonomy_name' => 'beauty-branch',
                'post_type' => 'beauty'
            ],
            [
                'taxonomy_title' => 'Space Types',
                'taxonomy_name' => 'space-type',
                'post_type' => 'space'
            ],
            [
                'taxonomy_title' => 'Space Amenities',
                'taxonomy_name' => 'space-amenity',
                'post_type' => 'space'
            ],
            [
                'taxonomy_title' => 'Tour Types',
                'taxonomy_name' => 'tour-type',
                'post_type' => 'tour'
            ],
            [
                'taxonomy_title' => 'Tour Include',
                'taxonomy_name' => 'tour-include',
                'post_type' => 'tour'
            ],
            [
                'taxonomy_title' => 'Tour Exclude',
                'taxonomy_name' => 'tour-exclude',
                'post_type' => 'tour'
            ]
        ];

        foreach ($data as $args) {
            $repo->create($args);
        }
    }
}
