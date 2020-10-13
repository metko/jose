# Templates

Wordpress provides a simple system to handle multiple template. You use the [template hierarchy rules](https://wphierarchy.com/) of wordpress to build what you want.

But sometime you need more control, and instead of having a `page.php` to handle all the post page, i can declare a template to use for a specific group of page/post.

Just create a new file in your templates folder of your theme( `/wp/app/themes/your_theme/templates/my-template.php`).

> Don't prefix you template by same name as wordpress does like `page-`, `single-`, `archive-`..It can confuse him when as he can take it for a normal page. 
> So a good convention will be `template-homepage.php` for example.

Once you created your template, you should pass a comment section at the top of the file with the template name like this:


```php
// Template Name: Template homepage 
```

Now, in your page/post attribute, you can see the template you just created. You can assign the new one and create a specific twig file for him.


```php
// /template/template-homepage.php
\Jose\Jose::site()->render('templates/template-homepage'); 
// File in /app/views/templates/template-homepage.twig
```

### Template for post type

The methods above is ok fo classic wordpress post. However, if you want to use customs templates for post type, you have to mentionned a post type name list in the comment of the template:

```php
// Template Name: Template homepage 
// Template Post Type: post, page, product, my_custom_post_name, etc...
```
