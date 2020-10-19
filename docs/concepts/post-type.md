# Post types

One of the most powerful features of wordpress is the process of the post type. Jose handle the creation of them very easly.

- First, make sur you have declared the `post_type_path` in your config file.

```php
 "post_type_path" => '/app/PostType'
```

> Make sure to have a file name equal to your class and to enabled namespacing loading in your composer.

Here, juste create a file `Book.php`

```php
//Book.php

namespace App\PostType;

use Jose\Posts\PostType;

class Book extends PostType {

    public static $name = "book";

}
```

That's it!

Now, you can pass other options like the public name, and the public plural name if needed

```php

public static $public_name = "livre";
public static $public_plural_name = "Livressssss";

```

> The $name propery will be remain the principal name for registrtion of the post type.

You can also pass the array of labels if you to change the global output.
```php

public static function define_labels(): array
{   
    $labels = [
        // ...
    ];
    return $labels;
}
```

And also the arguments of the post type config. Note that you can also pass every single property as property of the class.

```php

public static $support = ['comments', 'editor', // etc...]

// or passing a full array witout the labels

public static function define_arguments() {
    return [];
        $arguments = [
        // ...
    ];

    return $arguments;
}
 
```

### Hooks

You can use the Trait useHooks ans start add some behaviour to specific moment.

```php
namespace App\PostType;

use Jose\Posts\PostType;
use Jose\Posts\Traits\useHooks;

class Book extends PostType {

    use useHooks;

    // And now i can have a behaviour on the pre fetch post
    public static function pre_get_posts( $query ): WP_Query
    {
        // and update the query for this specific post type
        // you can use some helpfull method of worpdress

        // if (is_single()) {
            // bla bla bla
        }

        // don't forget to return the query
        return $query    
    }
}
```

Here's the list of the available hooks :

- before_register
- after_register
- after_save
- before_save
- after_update
- before_update
- pre_get_posts

And other usefull property like 

```php
public static $query_posts_limits = 9;
//etc
```