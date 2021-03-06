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
        "location" => "app/views/_cached",
    ],

    /*
    |--------------------------------------------------------------------------
    | Views path
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */    
    'views_path' => "app/views",
    'context_path' => "app/context.php",


    // Create spots name for menu and, assign page on the bo abnd call them with 
    // wp_nav_menu([ 'theme_location'  => 'main_menu']); 
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


    'image_size' => [
        "small" => '400x400'
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Assets
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */
    'assets' => [

        "manifest" => false,

        'css' => [
            'app.css',
        ],
        'js' => [
            'app.js'
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Post type and taxonomies path
    |--------------------------------------------------------------------------
    */

    "post_type_path" => "app/PostType",
    "taxonomies_path" => "app/Taxonomies",

    // must have acf pro
    "acf_block_path" => ''

];