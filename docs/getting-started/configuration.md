# Configuration

Jose comes with a built in configuration that you an find [here]('#'). He's assuming that you have a `/app` folder at your root web project, but if you want, this can be change into your config file.

## Custom configuration

You have two way to provide a custom config:

## Passing array
You can pass an array into the config method of jose to erase the default one and provide your owns. The best place to init a new config is in your `functions.php` of your theme.

```php
// functions.php
\Jose\Jose::config([
    'cache' => true,
    'wiew_path' => '/my/new/view/path'
])
```

## Passing file config path
Passing an file path relative to your webroot folder.

```php
// functions.php
\Jose\Jose::config("/conf/my_conf.php")
```
> TODO: Add support to yml and json type file.

> By default, Jose will look into an app folder and check for existence of a `config.php`, and merge it with the default one.






