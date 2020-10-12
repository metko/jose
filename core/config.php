<?php
namespace Core;

use ErrorException;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;

class Config {

    protected static $instance = null;
    private $fileSystem = null;
    private $configPath = null;

    protected $configFile = [];

    public function __construct() {
        $this->fileSystem = new Filesystem();
        $this->configPath = APP.'config/';
    }

    // *********
    // Get instance
    public static function getInstance(){
        if( ! self::$instance ) {
            self::$instance= new Config();
        }

        return self::$instance;
    }

    // ********************************
    // Get file and/with key in array
    public function get($file, $key = null) {

        // if the we have already load the config file, return it directly
        if(array_key_exists($file, $this->configFile)) {

            return $this->configFile[$file];
        }

        // check if the file exsist before
        if( ! $this->fileSystem->exists($this->configPath.$file.".php")) {
           throw new FileNotFoundException("File ".$this->configPath.$file.".php". " doesnt exists");
        }
      

        try{
            // require the file php ans saved into the 
            $this->configFile[$file] = require($this->configPath.$file.".php");

            // return the all array if no keys was passed in params
            if( ! $key ) {
                return $this->configFile[$file];
            }
            
            // if the key doenst exist
            // return the array or throw an error in dev
            if( ! array_key_exists($key ,$this->configFile[$file] ))  {
                if(WP_ENV == "development") {
                    throw new ErrorException("File ".$file." doesnt exists or key ".$key." doesnt exist" );
                }
                return false;
            }
            return $this->configFile[$file][$key];

        } catch(ErrorException $e) {
          
            return false;
        }
    }   

    function context () {
    // check if the file exsist before
        if( ! $this->fileSystem->exists(APP."context.php")) {
            throw new FileNotFoundException("Where is the app/context.php file ?");
        }

        return require(APP."context.php");
    } 

}