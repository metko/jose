<?php

namespace Jose\Core\Exceptions;

use Exception;

class MissingPostTypeInTaxonomiesException extends Exception {

    public function __construct($key, $class = null) {

        $this->message = "You need to attach your taxonomie '".$key. "' at one post type at least";

        ErrorsExceptions::getInstance()
            ->init([
                "type" => "MissingPostTypeInTaxonomies",
                "File" => $class,
                "Solution" => "Add a property post_types type array."
            ]);

        parent::__construct();
    }



}