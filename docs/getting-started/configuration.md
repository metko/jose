

# Configuration

Before you start using Jose, let's make some configuration:

### Assets

You can configure the differents assets you want to pass to your theme. We will assume that you wil store your assets in an `assets` folder in your theme.
Just pass the name of the foler you want to use: 
```php
'assets' => [
    'css' => [
        'app.css',
        'app2.css',
        // ...etc
    ],
    'js' => [
        'app.js',
        // ...etc
    ],
]
```

> If you leave the key empty, it wil bring by default a css and js file respectivly called `app.css` ans `app.js`

You can pass the option use_manifest by giving `manifest.json` name placed into the assets folder. You can use a package like Symfony encore to build this with ease.
 ```php
'assets' => [
    "use_manifest" => "manifest.json"
]
```
### Views

Use can change the default folder of the views by passing an absolute path from your root folder.

 ```php
'views_path' => "app/views",
```

### Caching

For caching, Jose uses the build in system provide by twig. You enable or disable it for your differents environments. 
It's also possible to change the folder of the cached views file. 

> More on caching process on the context section and rendering.

```php
'cache' => [
    "in_production" => true,
    "in_development" => false,
    "location" => "app/views/_cached",
],
```

### Local key

Local key will be used for generating translation of your wordpress menu. It will be passed each time in your global context, so like this you can't miss a traduction.

```php
"local_key" => "jose",
```






