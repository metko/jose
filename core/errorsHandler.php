<?php
Namespace Jose\Core;

use Jose\Utils\Config;

class ErrorsHandler {

    public static $instance = null;

    public $errorConfig = null;
        
    /**
     * init
     *
     * @return void
     */
    public function init() {
        
        $this->errorConfig = Config::getInstance()->get('errors');

        if(array_key_exists('drivers', $this->errorConfig)) {

            if( strtolower($this->errorConfig['drivers']) == "whoops") {
                $this->initWhoopsErrors();
            }

        }

    }
    
    /**
     * initWhoopsErrors
     *
     * @return void
     */
    public function initWhoopsErrors() {
        // dump('whoops init');
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }
    
    /**
     * getInstance
     *
     * @return \Jose\Core\ErrorsHandler
     */
    public static function getInstance() {
        if( ! self::$instance) {
            self::$instance = new ErrorsHandler();
        }
        return self::$instance;
    }
}