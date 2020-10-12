<?php

namespace Jose\Core;

use App\PostType\PostType as LocalPostType;
use ErrorException;
use Jose\Utils\Config;
use Symfony\Component\Filesystem\Filesystem;

class PostType {
    
    /**
     * The post type array
     * Will be injected the post type present in the config post_type file
     *
     * @var undefined
     */
    protected $posts = null;
        
    /**
     * Instance of file system
     * TODO: Refactor into a singleton
     *
     * @var undefined
     */
    protected $fileSystem = null;
    
    /**
     * Array of the model class to be registered
     *
     * @var array
     */
    protected $modelClass = [];
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {

        $this->fileSystem = new Filesystem();

        $this->posts = Config::getInstance()->get('post_type');

        if( count($this->posts)) {
            foreach($this->posts as $post) {
                $this->createPostType($post);
            }
        }
      
   
        // also, check in the folder for file in app/postType.php
        $this->loadClassFromFolder();
        
        
        $models = $this->modelClass;
        // and after register the class model
        add_filter('Timber\PostClassMap', function($post_class) use ($models) {
            return $models;
        });

        // postclassmap
      

    }

    
    /**
     * Load class from the post type folder for auto registration and get the models
     * 
     * @return void
     */
    public function loadClassFromFolder () {
        // $class = new LocalPostType();
        // dd($class);
    } 

    
    /**
     * createPostType
     *
     * @param  mixed $post
     * @return void
     */
    public function createPostType($post) {

        // Must have a name
        if( ! $post['name']) {
            throw new ErrorException('You mist provide a name to your post type.');
        }

        // define the name in plural if it's nto specified
        if(  ! array_key_exists("name_plural", $post)) {
            $post['name_plural'] = $post['name']."s";
        }

        // the 2 vars names
        $name = strtolower($post['name']);
        $pluralName = strtolower($post['name_plural']);

        // define a slug if it's notr
        if(  ! array_key_exists("slug", $post)) {
            $post['slug'] = $pluralName;
        }

        // get the localKey
        $localKey = "lk";

        // Generate the post type array
        $labels = $this->getLabelPostType($name, $pluralName, $localKey);
        $arguments = $this->getArgumentsPostType($post, $name, $pluralName, $localKey, $labels);


        // Check for the class model name

        if( ! array_key_exists('model_class_name', $post) )  {
            throw new ErrorException('You must provide a model classe name for the post type '. $name);
        }

      

        $class = "\App\Model\\".$post['model_class_name'];
        if(class_exists($class) ) {
            $this->modelClass[$name] = $class;
        }else {
           throw new ErrorException("Class " . $class . " doesnt exists.");
        }

        
        // Register the post type
        register_post_type($name, $arguments);

    }       
 
    /**
     * Generate the arguiment fileds    
     *
     * @param  mixed $post
     * @param  mixed $name
     * @param  mixed $pluralName
     * @param  mixed $localKey
     * @param  mixed $labels
     * @return array
     */
    public function getArgumentsPostType($post, $name, $pluralName, $localKey, $labels) {
        
        return [
            'labels'             => $labels,
            'description'        => __(ucfirst($pluralName).' post.', $localKey),
            'public'             => array_key_exists('public', $post) ? $post['public'] : true,
            'publicly_queryable' => array_key_exists('publicly_queryable', $post) ? $post['publicly_queryable'] : true,
            'show_ui'            => array_key_exists('show_ui', $post) ? $post['show_ui'] : true,
            'show_in_menu'       => array_key_exists('show_in_menu', $post) ? $post['show_in_menu'] : true,
            'query_var'          => array_key_exists('has_archive', $post) ? $post['has_archive'] : true,
            'rewrite'            => array('slug' => $post['slug'], 'with_front' =>array_key_exists('with_front', $post) ?  $post['with_front'] : true),
            'has_archive'        => array_key_exists('has_archive', $post) ? $post['has_archive'] : true,
            'hierarchical'       => array_key_exists('hierarchical', $post) ? $post['hierarchical'] : false,
            'menu_position'      => array_key_exists('menu_position', $post) ? $post['menu_position'] : null,
            'show_in_rest'       => array_key_exists('show_in_rest', $post) ? $post['show_in_rest'] : true,
            'menu_icon'          => array_key_exists('menu_icon', $post) ? $post['menu_icon'] : "dashicons-smiley",
            'supports'           => array_key_exists('supports', $post) ? $post['supports'] : ['title', 'page-attributes', 'thumbnail','revisions', 'editor', 'excerpt']
        ];
    }

    /**
     * Generate the label of the post type 
     *
     * @param  mixed $name
     * @param  mixed $pluralName
     * @param  mixed $localKey
     * @return array
     */
    public function getLabelPostType($name, $pluralName, $localKey){
        return [
            'name'               => _x( ucFirst($pluralName), 'Post type general name', $localKey ),
            'singular_name'      => _x( ucFirst($name), 'Post type singular name', $localKey ),
            'menu_name'          => _x( ucFirst($pluralName) , 'admin menu', $localKey ),
            'name_admin_bar'     => _x( ucFirst($pluralName) , 'add new on admin bar', $localKey ),
            'add_new'            => _x('Add '.$name, '$Project', $localKey ),
            'add_new_item'       => __('Add new '.$name, $localKey ),
            'new_item'           => __('New '.$name, $localKey ),
            'edit_item'          => __('Edit '.$name, $localKey ),
            'view_item'          => __('View '.$name, $localKey ),
            'all_items'          => __('All '.$pluralName, $localKey),
            'search_items'       => __('Search '.$pluralName, $localKey),
            'parent_item_colon'  => __('Parent :'.$pluralName , $localKey),
            'not_found'          => __('No '.$pluralName.' found.', $localKey),
            'not_found_in_trash' => __('No '.$pluralName.' found in Trash.', $localKey)
        ];
    }

}