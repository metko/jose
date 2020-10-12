<?php

namespace Jose\Core\Exceptions;

use Exception;

class MissingKeyExeption extends Exception {

    public function __construct($key) {
        
        $this->message = "The key '".$key. "' was not found in your configration file";
        
        ErrorsExceptions::getInstance()
          ->init([
            "type" => "MissingKeyExeption",
            "File" => $key,
            "Solution" => "Add a key named '".$key."' in your configuration file. (ex: '".$key."'=> 'value')"
          ]);

        parent::__construct();
    }


    
}