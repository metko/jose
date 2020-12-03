<?php

namespace Jose\Core;

use ErrorException;
use Jose\Utils\Config;
use Jose\Utils\Finder;
use Timber\Menu;
use Timber\Timber;

class Context {

    public $finder = null;

    public static $instance = null;

    public $context = [];


    public function __construct() {
        $this->finder = Finder::getInstance();
        $this->context = $this->get_user_context();
    }
 

    /**
     * Get the timber context merged to user context
     *
     */
    public function get(): array
    {
        $context = array_merge(Timber::context() ,$this->context);
        Timber::$context_cache = [];
        return $context;
    }

    /**
     * Pass varibale into the context
     *
     * @param  mixed $param1 
     * @param  mixed $param2
     * @return self
     */
    public function pass($param1 = null, $param2 = null) 
    {
     
        if(!$param1) {
            return $this;
        }
        if(is_array($param1)) {
            $this->addArrayToContext($param1);
        }else if( isset($param2)) {
            $this->context[$param1] = $param2;
        }
        return $this;
        
    }
    
    /**
     * Add an array to the context
     *
     * @param  mixed $array
     * @return null
     */
    public function addArrayToContext(Array $array) 
    {
        if( count($array)) {
            foreach($array as $key => $value) {
                $context[$key] = $value;
            }
        }
        $this->context = array_merge($this->context, $context);
    }


    
    /**
     * getInstance
     *
     * @return void
     */
    public static function getInstance(): Context 
    {
        if( ! self::$instance ) {
            self::$instance = new Context();
        }

        return self::$instance;
    }
    
    /**
     * Get the config key from config
     *
     * @return array
     */
    private function get_user_context(): array
     {
        $context_path = Config::getInstance()->get('context_path');
        if($context_path) {
            if($this->finder->file_exists(ROOT . $context_path)) {
                return $this->finder->require(ROOT . $context_path);
            }else {
                throw new ErrorException(ROOT . $context_path . " doesnt exists");
            }
        }
        return [];
    }
}