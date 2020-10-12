<?php

namespace Jose\Core\Exceptions;

use Exception;

class ConfigFileNotException extends Exception {

    public function __construct($file) {
        
        $this->message = "Config file not found: ".$file;
        
        ErrorsExceptions::getInstance()
          ->init([
            "type" => "ConfigFileNotException",
            "File" => $file,
            "Solution" => "You must use a configuration file located in app/config.php in order to user Gordo Jose"
          ]);

        parent::__construct();
    }


    
}