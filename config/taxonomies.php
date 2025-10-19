<?php

return [
    // Taxonomies model to be used
    'taxonomies_model' => App\Models\Taxonomy::class,

    // Taxonomy icon collection name for media library
    'icon_collection_name' => 'taxonomy_icons',
    'photo_collection_name' => 'taxonomy_photos',

    // Define conversions for taxonomy icons
    'icon_conversions' => [
        'small' => [80, 80], // Width, Height
        'medium' => [150, 150], // Width, Height
        'large' => [300, 300], // Width, Height
    ],

    // Specify whether slugs should be unique
    'unique_slugs' => true,

    // Translation to use for slugs
    'slug_locale' => 'en',

    //Slug separator
    'slug_separator' => '-',
];
