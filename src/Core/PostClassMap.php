<?php

namespace Jose\Core;

use Jose\Core\Exceptions\ClassNotFoundException;
use Jose\Utils\Config;

class PostClassMap {

    public $classMap = []; 
    
    public $config;

    public static $instance = null;

    public static function getInstance() {
        if( ! self::$instance ) {
            self::$instance = new PostClassMap();
        }

        return self::$instance;
    }

    public function __construct() {
        $this->config = Config::getInstance()->get("models");
    }

    public function add($postClass) {
        $this->classMap = array_merge($this->classMap, $postClass);
    }

    public function apply() {
        $this->addPostClassPage();
        // dd($this->classMap);
        $models = $this->classMap;
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
        if( $this->config && array_key_exists("pages_model", $this->config) &&  $this->config['pages_model'] ) {

            if(! class_exists($this->config['pages_model'])) {
                throw new ClassNotFoundException($this->config['pages_model']);
            }
            
            $this->classMap['page'] = $this->config['pages_model'];
        }else {
            $this->classMap['page'] = "\Jose\Models\PageModel";
        }
    } 

}