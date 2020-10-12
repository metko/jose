<?php

namespace Jose\Core\Exceptions;

use Exception;

class ClassNotFoundException extends Exception {

    public function __construct($name) {
        
        $this->message = "Class ".$name." doesnt exist";
        
        ErrorsExceptions::getInstance()
            ->init([
                "type" => "ClassNotFoundException",
                "Solution" => "Check the class name absolute path"
            ]);

        parent::__construct();
    }


    
}