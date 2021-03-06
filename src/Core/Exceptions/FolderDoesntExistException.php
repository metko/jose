<?php

namespace Jose\Core\Exceptions;

use Exception;

class FolderDoesntExistException extends Exception {

    public function __construct($folder) {

        $this->message = "Folder '".$folder. "' doesnt exists";

        ErrorsExceptions::getInstance()
            ->init([
                "type" => "FolderDoesntExistException",
                "Solution" => "Create folder $folder"
            ]);

        parent::__construct();
    }



}