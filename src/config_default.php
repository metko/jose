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
    // 'context_path' => "app/context.php",


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

    'image_size' => [],

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
    | Assets
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    "post_type_path" => "app/PostType",
    // "post_type_path" => "",

    "models" => [
        "path" => "app/models",
        //"post_type" => "\App\Models\PageModel",

        

        "taxonomies_model" => [
            [
                "model" => "\App\Models\Taxonomies\LevelModel",
                'unique_name' => "levels",
                "public_name" => "level", // The name to display in the admin section
                "public_plural_name" => "levels",
                "slug" => "levels",
                "post_types" => ['book']
            ],

            [
                "model" => false,
                'unique_name' => "genres",
                "public_name" => "Genre", // The name to display in the admin section
                "public_plural_name" => "Genres",
                "slug" => "genres",
                "post_types" => ['book']
            ],
        ],
    ],

    




];