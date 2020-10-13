<?php

namespace Jose\Core;

use ErrorException;
use Jose\Core\Exceptions\ClassNotFoundException;
use Jose\Traits\useRegisterPost;
use Jose\Utils\Config;

class Taxonomies {
    
    use useRegisterPost;

    public $type = "taxonomies";

    /**
     * post_types
     *
     * @var array
     */
    public $taxonomies = [];   


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
         
    }

           
 
   
}