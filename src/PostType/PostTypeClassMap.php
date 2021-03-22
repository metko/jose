<?php

namespace Jose\PostType;

class PostTypeClassMap
{
    public $postClass = [];

    public function add($post_type, $model) {
        $this->postClass[$post_type] = $model;
    }

}