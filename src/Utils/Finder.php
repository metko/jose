<?php

namespace Jose\Utils;

use ErrorException;
use Jose\Core\Exceptions\ConfigFileNotException;
use Jose\Core\Exceptions\ConfigIsNotArrayException;
use Jose\Core\Exceptions\FileNotException;
use Symfony\Component\Filesystem\Filesystem;

class Finder {

    public static $instance = null;

    public $fileSystem = null;
    public $finder = null;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        $this->fileSystem = new Filesystem();
        $this->finder = "";
    }
    
    /**
     * getInstance
     *
     * @return void
     */
    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new Finder();
        }
        return self::$instance;
    } 
    
    /**
     * fileExists
     *
     * @param  mixed $file
     * @return void
     */
    public function fileExists($file) {
        return $this->fileSystem->exists($file);
    }
    
    /**
     * require
     *
     * @param  mixed $file
     * @return void
     */
    public function require($file) {
        if(! $this->fileExists($file) ) {
            throw new ConfigFileNotException(APP.'config.php');
        }
        
        $config =  require($file);
        
        if(! is_array($config)) {
            throw new ConfigIsNotArrayException(APP.'config.php');
        }

        return $config;
    }

}