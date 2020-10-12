<?php

namespace Core\Setup\PostType;

use ErrorException;

class RegisterPostType {
    
    /**
     * The posttype array present in the child class
     *
     * @var array
     */
    protected $postType = [];

    public function __construct() {

        foreach($this->postType as $postType => $model) {

            $class = $this->getPostTypeClassName($postType);
            $model = $this->getModelClassName($model);

            if( ! class_exists($class)) {
                throw new ErrorException('Class '.$class. " not found");
            }

            if( ! class_exists($model)) {
                throw new ErrorException('Class '.$model. " not found");
            }

            new $class();

        }

        return $this->postType;
    }

    private function getPostTypeClassName($postType) {
        return '\App\PostType\\'.$postType;
    }

    private function getModelClassName($model) {
        return '\App\Model\\'.$model;
    }

}