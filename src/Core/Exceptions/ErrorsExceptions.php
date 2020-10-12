<?php

namespace Jose\Core\Exceptions;

use Whoops\Exception\ErrorException;
use Whoops\Run;

class ErrorsExceptions {

    public static $instance = null;

    public $run = null;
    public $handler = null;

    public function __construct () {
        $this->run     = new \Whoops\Run;
        $this->handler = new \Whoops\Handler\PrettyPageHandler;
    }

    public static function getInstance() {
        if( ! self::$instance ) {
            self::$instance = new ErrorsExceptions();
        }
        return self::$instance;
    }


    public function init(Array $options) {

        // Add some custom tables with relevant info about your application,
        // that could prove useful in the error page:
        $this->handler->addDataTable('Stack details', $options);
        
        // Set the title of the error page:
        $this->handler->setPageTitle("Whoops! There was a problem.");
        
        $this->run->pushHandler($this->handler);
        
        if (\Whoops\Util\Misc::isAjaxRequest()) {
            $this->run->pushHandler(new \Whoops\Handler\JsonResponseHandler);
        }

        $this->run->register();
        
    }

}