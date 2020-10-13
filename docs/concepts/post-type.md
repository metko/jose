# Post types

One of the most powerful features of wordpress is the process of the post type. Jose handle the creation of them with breeze. Let's take a look to a declaration in 2 steps:

- First, declare the post type in the `config.php`

```php
 "models" => [
    "post_model" => [
        "book" => [
            "unique_name" => "book",            // The unique name for the wordpress in db
            "model" => "\App\Models\BookModel", // The path of the model
            "public_name" => "book",            // The name to display in the admin section
            "public_plural_name" => "books",    // The name to display in the admin section
            "slug" => "books",                  // The name for the urls
        ],
    ]
]
```

- Two, create a Model associated to the post model object in the corresponding namespace we have declared  `App/Models/BookModel.php`

```php
namespace App\Models;

use Jose\Models\Model;

class BookModel extends Model {

}
```

That's it. So now, i have created the book post type. You can skip the second step and you will not use the model, it's an option. But it's higtly recommanded to use it like this for 2 raisons:

- First, i can redefine my post type attribute like this:

```php
namespace App\Models;

use Jose\Models\Model;

class BookModel extends Model {

    public $labels = [
        // My array of labels
    ]

    public $arguments['has_archive'] = false;

    // Or use the custom hooks before and after

    public function beforeRegister() {
        dd($this->arguments); // To see the object before his getting registred
        // then
        $this->arguments['key'] => $value
    }
    
    public function afterRegister() {
      // And a hook after the post type is registrer
      // Great place to setup some resctriction
      //For example
      $this->setQueryPostLimit();
    }
}
```
> Here you can find the complete post type arguments

> Keep in mine that the real name of the post type in created in the unique_name of the `config.php`


Let's see the second raison in the next section.