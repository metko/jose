<?php
namespace Tests\Ressources;

class Task{}


class TaskParams{

    public $name;
    public $status;

    public function __construct($name, $status) {
        $this->name = $name;
        $this->status = $status;
    }
}