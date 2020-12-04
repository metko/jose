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
     */
    public $post_type_path = null;    

    public $taxonomies_path = null;    

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

        if(! $this->taxonomies_path = Config::getInstance()->get("taxonomies_path") ) {
            $this->taxonomies_path = 'app/Taxomies';
        }
    }


    /**
     * init Register post type and taxonomy
     *
     * @return void
     */
    public function init(): void
    {
        
        $this->regiser_posts_types($this->post_type_path);
        $this->regiser_posts_types($this->taxonomies_path);

    }

  
    /**
     * regiser_posts_types
     *
     * @param  mixed $path
     * @return void
     */
    private function regiser_posts_types(string $path) :void 
    {
        // dump(Context::getInstance()->get());
        foreach ( Finder::getInstance()->getFiles(ROOT.$path) as $file ) {
                       
            // Get file path
            $file_path = $file->getRelativePathname();

            // Convert into a class namespace accessible
            $class_name = pathToNamespace($path) . explode('.', $file_path)[0];
            
            // Then register the post type$$
            $class = new $class_name();
            $class ->register_post_type();
           
            
            // If a post_model it's use, register it
            if( property_exists( $class, "post_model" ) ) {
               
                $this->register_post_model($class);
            }
        }
    }

    
    /**
     * register_post_model
     *
     * @param  string $class_name
     * @return void
     */
    private function register_post_model (object $class) :void
    {
        $class_model = $class->post_model;

        if( ! class_exists($class_model)) {
            throw new ErrorException('Model '. $class->post_model .' doesnt exists');
        }
        //dump($class->name, $class->post_model);
        // auto generate class model
        PostClassMap::getInstance()->add([$class->name => $class->post_model]);
    }

      
 
   
}