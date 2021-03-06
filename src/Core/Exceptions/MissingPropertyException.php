<?php

namespace Jose\Core\Exceptions;

use Exception;

class MissingPropertyException extends Exception {

    public function __construct($key, $class = null) {

        $this->message = "Property '".$key. "' is missing in the class";

        if($class) {
            $this->message .= " $class";
        }

        ErrorsExceptions::getInstance()
            ->init([
                "type" => "MissingProperty",
                "File" => $class,
                "Solution" => "Add a property named '".$key."' in class."
            ]);

        parent::__construct();
    }



}