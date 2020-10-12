<?php

namespace Jose\Utils;

use ErrorException;
use Symfony\Component\Filesystem\Filesystem;

class Finder {

    public static $instance = null;

    public $fileSystem = null;
    public $finder = null;

    public function __construct() {
        $this->fileSystem = new Filesystem();
        $this->finder = "";
    }

    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new Finder();
        }
        return self::$instance;
    } 

    public function fileExists($file) {
        return $this->fileSystem->exists($file);
    }

    public function require($file) {
        if(! $this->fileExists($file) ) {
            // TODO throw error ConfFileNotFound
            throw new ErrorException('File not found in :' .APP.'config.php');
        }
        
        return require($file);
    }

}