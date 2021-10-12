<?php

namespace Jose\Models;

class BaseModel {

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        } else if (method_exists($this, $property)) {
            return $this->$property();
        }
    }  
}