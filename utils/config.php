<?php

namespace Jose\Utils;

use ErrorException;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class Config {
    
    public static $instance = null;

    public $config = [];

    public $finder = null;

    public function __construct() {
        $this->finder = Finder::getInstance();
    }

    // ********************************
    // Get file and/with key in array
    public function get($key = null) {

        if(!$key) {
            return $this->config;
        }

        if( ! array_key_exists($key , $this->config ))  {
            throw new ErrorException("Cponfiguraion key ".$key." doesnt exists" );
        }

        return $this->config[$key];

    } 
    
    public function getContext() {
        return $this->finder->require(APP.'context.php');
    }

    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new Config();
        }
        return self::$instance;
    } 


    public function init() {
        $this->config = $this->finder->require(APP.'config.php');
    }

}