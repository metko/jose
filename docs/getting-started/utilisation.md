# Utilisation

## Init

First, you need to instanciate jose on your theme.

For that, in the `functions.php`of your theme, juste called the `init` method

```php
Jose\Jose::app()->init();
```

> TODO: Use a wordpress hook to auto instanciate Jose

## Page and post

By default, Jose will guess by himself if we are in a page, a post, an archive etc.. with the built in function of wordpress. With that information, it will be add automaticly ion the context the post model needed.

> More information on post type and models on this section

So, basically, it's pretty to render a page:

### Render a view

```php
// In single.php
Jose\Jose::site()->render('my-view.twig');
```

Your post object will be available in your `context['post']` key:

```twig
<!-- In your twig file -->
{{ post.post_title }}
```

### Context

You can obviously pass more data to the context by calling the `pass` methods:

```php
// In single.php
Jose\Jose::site()
    ->pass('my_new_var', 'my new_value')
    // or 
    ->pass([
        'my_new_var' => 'my_new_value',
        "another_new_key" => "another_new_value
    "])
    ->render('my-view.twig');
```





