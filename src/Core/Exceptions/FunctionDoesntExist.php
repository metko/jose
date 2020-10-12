<?php

namespace Jose\Core\Exceptions;

use Exception;

class FunctionDoesntExist extends Exception {

    public function __construct($name) {
        
        $this->message = "Function ".$name." doesnt exist";
        
        ErrorsExceptions::getInstance()
            ->init([
                "type" => "FunctionDoesntExist",
                "Solution" => "Maybe you need to install a wordpress plugin of import a specific function somewhere...or write it!"
            ]);

        parent::__construct();
    }


    
}