<?php

namespace Jose\Core;

use ErrorException;
use Jose\Core\Exceptions\ErrorsExceptions;
use Jose\Utils\Config;
use Jose\Utils\Finder;

class PostClass {
        
    //use useRegisterPost;

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
    public $post_type_path = null;    

    public $type = "post_types";
    
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() 
    {
        if(! $this->post_type_path = Config::getInstance()->get("post_type_path") ) {
            $this->post_type_path = 'app/PostType';
        }
    }


    public function init() 
    {
        
        
        $this->regiser_posts_types($this->post_type_path);
        // Foreach file find into the folder path
        
        dd();
    }

    
    /**
     * regiser_posts_types
     *
     * @param  mixed $path
     * @return void
     */
    private function regiser_posts_types(string $path) :void 
    {
        foreach ( Finder::getInstance()->getFiles(ROOT.$path) as $file ) {

            // Get file path
            $file_path = $file->getRelativePathname();

            // Convert into a class namespace accessible
            $class_name = pathToNamespace($path) . explode('.', $file_path)[0];

            // Then register the post type
            $class_name::register_post_type();

            // If a post_model it's use, register it
            if( property_exists( $class_name, "post_model" ) ) {
                $this->register_post_model($class_name);
            }
        }
    }

    
    /**
     * register_post_model
     *
     * @param  string $class_name
     * @return void
     */
    private function register_post_model (string $class_name) :void
    {
        $class_model = $class_name::$post_model;

        if( ! class_exists($class_model)) {
            throw new ErrorException('Model doesnt exists');
        }

        // auto generate class model
        PostClassMap::getInstance()->add([$class_name::$name => $class_name::$post_model]);
    }

      
 
   
}