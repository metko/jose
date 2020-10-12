<?php
namespace Core\Setup;

use Core\Config;

class ErrorHandler {

    public $handler = null;
    
    public function __construct () {

        $this->handler = Config::getInstance()->get('app', "errorHandler");
        if( $this->handler && $this->handler['driver']) {
              if( $this->handler['driver'] == "whoops") {
                  $this->registerWhoops();
              }  
        }
    }

    public function registerWhoops() {
        if(WP_ENV == 'development' || $this->handler['use_in_production']) {

            // TODO 
            // COnfigure the whoops with https://github.com/filp/whoops/blob/master/docs/API%20Documentation.md
            $whoops = new \Whoops\Run;
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            $whoops->register();
        }
    }   
}