<?php

namespace Jose\Core;

use ErrorException;
use Jose\Core\Exceptions\ClassNotFoundException;
use Jose\Traits\useRegisterPost;
use Jose\Utils\Config;

class PostType {
        
    use useRegisterPost;

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
    public function __construct() {

        // ****************
        // Get the models key in config.php
        $this->config = Config::getInstance()->get("models");

       
        $this->post_types =  $this->config['post_model'] ? $this->config['post_model'] : []; 
        
    }

      
 
   
}