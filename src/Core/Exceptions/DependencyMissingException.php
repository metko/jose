<?php

namespace Jose\Core\Exceptions;

use Exception;

class DependencyMissingException extends Exception {

    public function __construct($depedency) {
        
        $this->message = "A php dependency is missing: ".$depedency;
        
        ErrorsExceptions::getInstance()
          ->init([
            "type" => "DependencyMissing",
            "Library" => "Timber",
            "Solution" => "Composer install Timber/Timber"
          ]);

        parent::__construct();
    }


    
}