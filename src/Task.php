<?php

namespace  Jose;

class Task {

    private $status;
    private $name;

    public function __construct ($name, $status) {
        $this->name = $name;
        $this->status = $status;
    }

    public function getName() {
        echo "hello";
    }
}