# Utilisation
Build your php theme file like in any theme

### Variables
```php
# single.php

jose()
    // Passing custom variable to the view   
    ->pass('my_new_var', 'my new_value')
    // or 
    ->pass([
        'book' => new Book(),
        "another_new_key" => "another_new_value
    "])
    // Will load the vue single.twig
    ->render('single');
```

### Context
You can also access to the context any time simply by calling `context();` in your app.
```php
# single.php

jose()
    // Passing custom variable to the view   
    ->pass('title', context('site.title')." - " . context('wp-title'))
    // Will load the vue single.twig
    ->render('single');
```
To get nested key, use the dot synthax `context('my.nested.key');`

You can register asset for the current page like this:

```php
# single.php

load_assets('single.css');
load_assets('single.js');
```
Those assets are now available only on this page

You can of course apply custom logic on it
```php
# single.php
if(context('post.id') == 12) {
    load_assets('single.js');
    load_assets('single.css');
}
```

Those assets will be available across your manifest key if you use one.

### Get image
In you php file
You can of course apply custom logic on it
```php
# single.php

jose()->pass('my_img', img_url('logo.png'));
```
or
```twig
# header.twig

<img src="{{ img_url('logo.png') }}" />
```
will return the url for you image supposed on a images folder

Use `assets_url()` if you want an url pointing into your dist folder


