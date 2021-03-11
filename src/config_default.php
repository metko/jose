<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cache configuration
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */
    'cache' => [
        "in_production" => true,
        "in_development" => false,
        "location" => "jose/Views/_cached",
    ],

    /*
    |--------------------------------------------------------------------------
    | Views path
    |--------------------------------------------------------------------------
    |
    | The folder of yours twig views
    |
    */    
    'views_path' => "jose/Views",

    /*
    |--------------------------------------------------------------------------
    | Blocks path
    |--------------------------------------------------------------------------
    |
    | The folder of your guttenbergs views
    |
    */
    'block_path' => "jose/Blocks",

    /*
    |--------------------------------------------------------------------------
    | ACF path
    |--------------------------------------------------------------------------
    |
    | The folder of custom acf fields
    |
    */
    'acf_fields_path' => "jose/Fields",

    /*
    |--------------------------------------------------------------------------
    | Context file path
    |--------------------------------------------------------------------------
    |
    | The path for you global context
    |
    */
    'context_path' => "jose/context.php",

    /*
    |--------------------------------------------------------------------------
    | Post type folder path
    |--------------------------------------------------------------------------
    |
    | The path for post type
    |
    */
    "post_type_path" => "jose/PostType",

    /*
    |--------------------------------------------------------------------------
    | Taxonomy folder path
    |--------------------------------------------------------------------------
    |
    | The path for post type
    |
    */
    "taxonomies_path" => "jose/Taxonomies",


    /*
    |--------------------------------------------------------------------------
    | Application menus
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */
    'menus_slot' => [
        'main_menu' => "Main menu",
        'footer_menu' => "Footer menu"
    ],

    /*
    |--------------------------------------------------------------------------
    | Images
    |--------------------------------------------------------------------------
    |
    | Default size when you upload a file
    |
    */
    'image_size' => [
        "small" => '400x400'
    ],

    /*
    |--------------------------------------------------------------------------
    | Assets
    |--------------------------------------------------------------------------
    */
    'assets' => [

        "manifest" => null,
        "dist_folder" => 'dist',

        'css' => [
            'app.css',
        ],
        'js' => [
            'app.js'
        ],
    ],

];