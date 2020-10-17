<?php

namespace Jose\Core;
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
    public $post_types = [];    

    public $type = "post_types";
    
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() 
    {

        // ****************
        // Get the models key in config.php
        $this->config = Config::getInstance()->get("models");
        //$this->post_types =  $this->config['post_model'] ? $this->config['post_model'] : []; 
        
    }


    public function init() 
    {
        
        // get the file in the folder
        $path = Config::getInstance()->get("post_type_path");

        if(!$path) {
            $path = 'app/PostType';
        }


        $files = Finder::getInstance()->getFiles(ROOT.$path);
        $namespaceArray = explode('/', $path);
        $namespacePath = "\\";
        foreach($namespaceArray as $key) {
            $namespacePath .= ucfirst($key);
            $namespacePath .= "\\";
        }
        //$namespacePath = join('\\', $namespaceArray);
        foreach ($files as $file) {

            $absoluteFilePath = $file->getRealPath();
            $fileNameWithExtension = $file->getRelativePathname();
            $className = explode('.', $fileNameWithExtension)[0];
            //equire_once($absoluteFilePath);
            $className = $namespacePath . $className;
            dump($className);
            $className::register_post_type();
            // ...
        }
        dd();

    }

      
 
   
}