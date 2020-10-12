<?php

namespace Jose\Core;

use ErrorException;
use Jose\Core\Exceptions\ClassNotFoundException;
use Jose\Utils\Config;

class PostType {
        
    /**
     * config
     *
     * @var array
     */
    public $config = [];    
    
    /**
     * post_types
     *
     * @var array
     */
    public $post_types = [];    
    
    /**
     * taxonomies
     *
     * @var array
     */
    public $taxonomies = [];

    /**
     * postClassMap
     *
     * @var array
     */
    public $postClassMap = [];
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {

        // ****************
        // Get the models key in config.php
        $this->config = Config::getInstance()->get("models");

        $this->taxonomies = $this->config['taxonomies_model'] ? $this->config['taxonomies_model'] : [];
       
        $this->post_types =  $this->config['post_model'] ? $this->config['post_model'] : []; 
        
    }

    /**
     * Init the post registration of model
     *
     * @return void
     */
    public function init() {
        
        // ****************
        // Create post type from count from the array of config.php
        if( count($this->post_types)) {
            foreach($this->post_types as $post) {
                $this->createPostType($post);
            }
        }

        // ****************
        // Add the model page model for the class map from timber
        $this->addPostClassPage();
        
        // ****************
        // Add the custom model to the autoload class map from timber
        $models = $this->postClassMap;
        add_filter('Timber\PostClassMap', function($post_class) use ($models) {
            return $models;
        });
    }

    
    /**
     * Get the pae model to use and add into the array for auto registration
     * of class from timber
     * 
     * @return void
     */
    public function addPostClassPage () {

        if( array_key_exists("pages_model", $this->config) &&  $this->config['pages_model'] ) {

            if(! class_exists($this->config['pages_model'])) {
                throw new ClassNotFoundException($this->config['pages_model']);
            }

            $this->postClassMap['page'] = $this->config['pages_model'];
        }else {
            $this->postClassMap['page'] = "\Jose\Models\PageModel";
        }
    } 

    
    /**
     * createPostType
     *
     * @param  mixed $post
     * @return void
     */
    public function createPostType($post) {
        
        // Check if the key name is set, it's required
        if( ! array_key_exists("unique_name", $post) || !$post['unique_name']) {
            throw new ErrorException("Key 'name' doesnt exist in the declaration ofthe post type"); 
        }

        $class = $post['model'];

        // Throw an error if the class doesnt exists
        if(! class_exists($class)) {
            throw new ClassNotFoundException($class);
        }
        //Add the class for for the autoClassMap by timber
        $this->postClassMap[$post['unique_name']] = $class;

        // Register the class
        (new $class())->register_post_type($post);

    }       
 
   
}