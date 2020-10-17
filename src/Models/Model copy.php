<?php

// namespace Jose\Models;

use Jose\Traits\useHooks;
use Jose\Utils\Config;
use Timber\Post;

class ModelCopie extends Post {

    use useHooks;
    
    /**
     * The locak key for translation
     *
     * @var undefined
     */
    public $local_key = null;   

    /**
     * Define or nor if we want to use the hooks auto
     *
     * @var undefined
     */
    public $hooks = false;   
      
    /**
     * Post ID
     *
     * @var undefined
     */
    // public $ID = null;
    
    /**
     * The unique name
     * It will be use for the registration of the post type
     *
     * @var undefined
     */
    public $unique_name = null;
        
    /**
     * The public name dispplayed in menus
     *
     * @var undefined
     */
    public $public_name = null;    
    
    /**
     * The plural name dispplayed in menus
     *
     * @var undefined
     */
    public $public_plural_name = null;
        
    /**
     * The post type slug for urls
     *
     * @var undefined
     */
    public $post_type_slug = null;
    
    /**
     * The array labels for menus
     *
     * @var array
     */
    public $menu_labels = [];

    public $argumentsPostType = [
        'labels' => null,
        'description' => '',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'rewrite' => [],
        'query_var' => true,
        'has_archive' => true,
        'hierarchical' => true,
        'menu_position' => null,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-block-default',
        'supports' => ['title', 'page-attributes', 'thumbnail','revisions', 'editor', 'excerpt']
    ];

    public $argumentsTaxonomies = [
        'labels' => null,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var' => true,
    ];


    /**
     * Get the local key
     * Set the name, label and arguments
     * @param  mixed $post
     * @return void
     */
    public function __construct($id = null) {
        // Contrtruct to init the timber model aswell
        parent::__construct($id);
        // $this->ID = $id;
    }
    
    /**
     * Set the name if somtehnig has been rewrite in the child model
     *
     * @param  mixed $post
     * @return void
     */
    public function setPostTypeName($post) {

        // The most important name
        $this->unique_name =  $post['unique_name'];
        
        if( ! $this->public_name ) {
            $this->public_name = array_key_exists('public_name', $post) ? $post['public_name'] : $post['unique_name'] ;
        }

        if( ! $this->public_plural_name ) {
            $this->public_plural_name = array_key_exists('public_plural_name', $post) ? $post['public_plural_name'] : $this->public_name."s" ;
        }
        if( ! $this->post_type_slug ) {
            $this->post_type_slug = array_key_exists('slug', $post) ? $post['slug'] : "" ;
        }

    }
 

    
    /**
     * Register the post type
     * 
     * Kind of init function
     */
    public function register_post_types($post) {


        // Get the local key for all the translation
        $this->local_key = Config::getInstance()->get('local_key');
        
        // Define the global name and plural name
        $this->setPostTypeName($post);

        // Generate the label
        $this->menu_labels = $this->generateLabelsPostType();

        // Set the final array object
        $this->argumentsPostType['labels'] = $this->menu_labels;
        $this->argumentsPostType['rewrite']= ['slug' => $this->post_type_slug, 'with_front' => true ];
        $this->argumentsPostType['description']= "Post type description";

        // Hooks before register post type
        $this->beforeRegister();

        //register
        register_post_type($this->unique_name, $this->argumentsPostType);

        // hooks after register post type
        $this->afterRegister();
        
        if($this->autoHooks) {
            $this->registerHooks();
        }
    }

    /**
     * Register the post type
     * 
     * Kind of init function
     */
    public function register_taxonomies($post) {
        
        // Get the local key for all the translation
        $this->local_key = Config::getInstance()->get('local_key');
        
        // Define the global name and plural name
        $this->setPostTypeName($post);

        // Generate the label
        $this->menu_labels = $this->generateLabelsTaxonomies();

        // Set the final array object
        $this->argumentsTaxonomies['labels'] = $this->menu_labels;
        $this->argumentsTaxonomies['rewrite']= ['slug' => $this->post_type_slug, 'with_front' => true ];
        $this->argumentsTaxonomies['description']= "Taxonomies description";

        // Hooks before register post type
        $this->beforeRegister();
        // dd($this->argumentsTaxonomies);
        //register
        register_taxonomy($this->unique_name, $post['post_types'] ,$this->argumentsTaxonomies);

        // hooks after register post type
        $this->afterRegister();
        
        if($this->hooks) {
            $this->registerHooks();
        }
    }
    
    
        
    /**
     * Generate the post type label 
     *
     * @return array
     */
    public function generateLabelsPostType(){

        // If the menus label has been set in the child model
        // use it instead
        if(count($this->menu_labels)) {
            return $this->menu_labels;
        }

        return  [
            'name'               => _x( ucFirst($this->public_plural_name), 'Post type general name', $this->local_key ),
            'singular_name'      => _x( ucFirst($this->public_name), 'Post type singular name', $this->local_key ),
            'menu_name'          => _x( ucFirst($this->public_plural_name) , 'admin menu', $this->local_key ),
            'name_admin_bar'     => _x( ucFirst($this->public_plural_name) , 'add new on admin bar', $this->local_key ),
            'add_new'            => _x('Add '.$this->public_name, '$Project', $this->local_key ),
            'add_new_item'       => __('Add new '.$this->public_name, $this->local_key ),
            'new_item'           => __('New '.$this->public_name, $this->local_key ),
            'edit_item'          => __('Edit '.$this->public_name, $this->local_key ),
            'view_item'          => __('View '.$this->public_name, $this->local_key ),
            'all_items'          => __('All '.$this->public_plural_name, $this->local_key),
            'search_items'       => __('Search '.$this->public_plural_name, $this->local_key),
            'parent_item_colon'  => __('Parent :'.$this->public_plural_name , $this->local_key),
            'not_found'          => __('No '.$this->public_plural_name.' found.', $this->local_key),
            'not_found_in_trash' => __('No '.$this->public_plural_name.' found in Trash.', $this->local_key)
        ];
    }

    /**
     * Generate the post type label 
     *
     * @return array
     */
    public function generateLabelsTaxonomies(){

        // If the menus label has been set in the child model
        // use it instead
        if(count($this->menu_labels)) {
            return $this->menu_labels;
        }

        return  [
            'name'                       => _x( ucFirst($this->public_plural_name), 'Post type general name', $this->local_key ),
            'singular_name'              => _x( ucFirst($this->public_name), 'Post type singular name', $this->local_key ),
            'search_items'               => __('Search '.$this->public_plural_name, $this->local_key),
            'popular_items'              => __('Popular '.$this->public_plural_name, $this->local_key),
            'all_items'                  => __('All '.$this->public_plural_name, $this->local_key),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => __('Edit '.$this->public_name, $this->local_key ),
            'update_item'                => __('Update '.$this->public_name, $this->local_key ),
            'add_new_item'               => __('Add new '.$this->public_name, $this->local_key ),
            'new_item_name'              => __('New '.$this->public_name.' name', $this->local_key ),
            'separate_items_with_commas' => __( 'Separate '.$this->public_plural_name.' with commas', $this->local_key ),
            'add_or_remove_items'        => __( 'Separate '.$this->public_plural_name.' with commas', $this->local_key ),
            'choose_from_most_used'      => __( 'Separate '.$this->public_plural_name.' with commas', $this->local_key ),
            'choose_from_most_used'      => __( 'Choose from the most used '.$this->public_plural_name, $this->local_key ),
            'menu_name'                  => _x( ucFirst($this->public_plural_name) , 'admin menu', $this->local_key ),
        
        ];

    }



}