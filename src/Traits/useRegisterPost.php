<?php

namespace Jose\Traits;

use ErrorException;
use Jose\Core\Exceptions\ClassNotFoundException;
use Jose\Core\PostClassMap;

Trait useRegisterPost {


    public $postClassMap = [];

    /**
     * Init the post registration of model
     *
     * @return void
     */
    public function init() {
        
        // ****************
        // Create post type from count from the array of config.php
        $type = $this->type;
        if( count($this->$type)) {
            foreach($this->$type as $post) {
                $this->createPostEntity($post);
            }
        }
        

        // ****************
        // Add the class map list to the global post class instance
        PostClassMap::getInstance()->add( $this->postClassMap);
        
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
     * Create taxonomy post
     *
     * @param  mixed $post
     * @return void
     */
    public function createPostEntity($post) {
        
        // Check if the key name is set, it's required
        if( ! array_key_exists("unique_name", $post) || !$post['unique_name']) {
            throw new ErrorException("Key 'name' doesnt exist in the declaration of the taxonomy"); 
        }

        $class = $post['model'];
        if(! $class ) {
            $class= "\Jose\Models\TaxonomyModel";
        }else {
            if(! class_exists($class)) {
                if($this->type == "post_types") {
                   throw new ClassNotFoundException($class);
                }
            }
        }
        // Throw an error if the class doesnt exists

        //Add the class for for the autoClassMap by timber
        $this->postClassMap[$post['unique_name']] = $class;
        // dump($this->postClassMap);
        // dd($class);
        // Register the class
        $methods = "register_".$this->type;
        //dd($methods);
        (new $class())->$methods($post);

    }

}