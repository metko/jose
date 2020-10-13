# Model

The power of using models is that we can use custom methods across of our model.
For example, i want to get get the author_name of all my post book.

Very easy, in my model `BookModel.php`

```php
namespace App\Models;

use Jose\Models\Model;

class BookModel extends Model {

    // simple singleton to gcache the data
    public $_author_name = null;

    public function author_name() {
        if(!$this->_author_name) {

            // I get this field from acf
            $field = $this->meta('author_name');

            $this->_author_name = $field
        }
        return $this->_author_name
    }
}
```

Now, in any place in my template i use a BookModel, in a loop for example, i can have this property `author_name`.

```twig
<ul>
    {% for post in posts %}
        <li>{{ post.author_name }}</li>
    {% endfor %}
</ul>
```

I can like retreive categories list, terms, formated date etc...

> - Another benefits of this, is that i can update the logic inside a methods without changing my template
> - I can extract complex operation in new file class

Let's see a more complexe example


```php
namespace App\Models;

use Jose\Models\Model;

class FormationModel extends Model {

    // simple singleton to gcache the data
    public $_type_formation_list = null;

    public $_class_formation_list = null;

    public function all_type_formation_list() {
        if(!$this->_type_formation_list) {
            // Get the list of class for this formation
            $classList = $this->class_formation_list();
            
            $this->_type_formation_list = $this->processTheArray($classList)
            // return "Virtuel, Presentiel, Other one"
        }
        return $this->_type_formation_list
    }

    public function class_formation_list() {
         if(!$this->_class_formation_list) {
            // Get the list of class for this formation
            $classList = $this->meta('class_list');
            // return [
                       //     [
            //         "place" => "Paris",
            //         "type" => [
            //             [
            //                 "name" => "Présentiel
            //             ]
            //         ],
            //     ],
            //     [
            //         "place" => "Berlin",
            //         "type" => [
            //             [
            //                 "name" => "Virtuel
            //             ], 
            //             [
            //                 "name" => "other one 
            //             ]
            //         ],
            //     ]

            // ]
        }
        return $this->_class_formation_list
    }

    public function processTheArray ($array) {
        // ...
        return $newArray
    }
    }
}
```
In my template:
```twig
<ul>
    {% for post in posts %}
        <li>{{post.post_name}}</li>  
        <li>{{post.all_type_formation_list}}</li>
    {% endfor %}
</ul>
```