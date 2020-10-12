<?php

namespace Jose\Core\Exceptions;

use Exception;

class FileNotException extends Exception {

    public function __construct($file) {
        
        $this->message = "File not found: ".$file;
        
        ErrorsExceptions::getInstance()
          ->init([
            "type" => "FileNotFoundException",
            "File" => $file,
          ]);

        parent::__construct();
    }


    
}