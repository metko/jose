# Configuration

Jose comes with a built in configuration that you an find [here]('#').
By default, Jose will use the following path config:
```php
[
     /******************************/
     /* The view template directory
     /******************************/
    'views_path' => "jose/Views",
    
     /******************************/
     /* Where your gutenberg block will be stored
     /******************************/
    'block_path' => "jose/Blocks",
    
     /******************************/
     /* Custom ACF field data
     /******************************/
    'acf_fields_path' => "jose/Fields",

     /******************************/
     /* Your post type definition
     /******************************/
    "post_type_path" => "jose/PostType",
    
     /******************************/
     /* Your taxonomy definition
     /******************************/
    "taxonomies_path" => "jose/Taxonomies",
    
    /******************************/
    /* The global context used by timber
    /******************************/
    'context_path' => "jose/context.php",
    
    /******************************/
    /* Assets configuration  
    /******************************/
    'assets' => [
        /******************************/
        /* The name of you manifest json
        /* You can leave it empty if you don't use it
        /******************************/
        "manifest" => 'manifest.json',

        /******************************/
        /* Name of your compiled folder in your theme for assets
        /******************************/
        "dist_folder" => 'dist',

        /******************************/
        /* Assets in order you want to load when wp start
        /* It will use the manifest key if you use one
        /* Otherwise, the filepath relative to yuor dist_folder
        /******************************/
        'css' => [
            'app.css',
        ],
        'js' => [
            'app.js'
        ],

    ],
    #...
];
```
You can change this by passing your iwn custom config file overind

## Custom configuration

You have two way to provide a custom config:

## Passing array
You can pass an array into the config method of jose to erase the default one and provide your owns. The best place to init a new config is in your `functions.php` of your theme.

```php
// functions.php
Jose\Jose::init([
    'cache' => true,
    'wiew_path' => '/my/new/view/path'
]);
```

## Passing file config path
Passing an file path relative to your webroot folder.

```php
// functions.php
\Jose\Jose::init("/conf/my_conf.php");
```
> TODO: Add support to yml and json type file.

> By default, Jose will look into an app folder and check for existence of a `config.php`, and merge it with the default one.






