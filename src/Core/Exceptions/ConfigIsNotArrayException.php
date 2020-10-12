<?php

namespace Jose\Core\Exceptions;

use Exception;

class ConfigIsNotArrayException extends Exception {

    public function __construct($file) {
        
        $this->message = "Config file must return an array: ".$file;
        
        ErrorsExceptions::getInstance()
          ->init([
            "type" => "ConfigIsNotArrayException",
            "File" => $file,
            "Solution" => "You must return an array in your configrations files. ( ex: return []; )"
          ]);

        parent::__construct();
    }


    
}