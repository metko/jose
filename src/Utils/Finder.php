<?php

namespace Jose\Utils;

use ErrorException;
use Jose\Core\Exceptions\ConfigFileNotException;
use Jose\Core\Exceptions\ConfigIsNotArrayException;
use Jose\Core\Exceptions\FileNotException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder as SymfonyFinder;

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
        $this->finder = new SymfonyFinder();
    }
    
    /**
     * getInstance
     *
     * @return 
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
    public function file_exists($file) {
        return $this->fileSystem->exists($file);
    }
    
    /**
     * require
     *
     * @param  mixed $file
     * @return void
     */
    public function require($file) {
       
        
        $config =  require($file);
        
        if(! is_array($config)) {
            throw new ConfigIsNotArrayException($file);
        }

        return $config;
    }

    public function getFiles($path) {
       return $this->finder->files()->in($path);
    }

    

}