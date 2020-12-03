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
    'menus' => [
        'main_menu' => "Main menu"
    ],


    'image_size' => [
        "small" => '400x400'
    ],

    /*
    |--------------------------------------------------------------------------
    | Local key
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */
    "local_key" => "jose",

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
    | Assets
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */
    "breadcrumbs" => [

        'use_yoast_seo' => false,

        'yoast' => [
            'start' => "<div class='breadcrumb'>",
            'end' => '</div>',
        ],

        // 'custom_class' => "\App\YourBreadcrumbClass"
        'custom_class' => false
    ],

    /*
    |--------------------------------------------------------------------------
    | Post type and taxonomies path
    |--------------------------------------------------------------------------
    */

    "post_type_path" => "app/PostType",
    "taxonomies_path" => "app/Taxonomies",
    "plugins_conf_path" => "config/plugins.php"

];