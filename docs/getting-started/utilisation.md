# Utilisation

```php
\Jose\Jose::app()
    ->pass('my_new_var', 'my new_value')
    // or 
    ->pass([
        'book' => new Book(),
        "another_new_key" => "another_new_value
    "])
    ->post()
    ->render('my-view.twig');
```

> More doc comming






