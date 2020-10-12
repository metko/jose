<?php

namespace Jose\Core\Exceptions;

use Exception;

class ManifestAssetsNotFound extends Exception {

    public function __construct() {
        
        $this->message = "The manifest.json doesn't exist";
        
        ErrorsExceptions::getInstance()
          ->init([
            "type" => "ManifestAssetsNotFound",
            "Solution" => "With a bundler of your choice, you need to generate a manifest.json in the assets folder of your theme."
          ]);

        parent::__construct();
    }


    
}