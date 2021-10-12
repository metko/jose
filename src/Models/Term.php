<?php

namespace Jose\Models;

class Term extends BaseModel {
    public $id = '';
    public $name = '';
    public $slug = '';
    public $taxonomy = '';
    public $description = '';
    public $parent = '';
    public $count = '';
    
    public function __construct($term) {
        //dd('$post', $post);
        $this->convert($term);
    }
    public function convert($term) {
        $this->id = $term->term_id;
        $this->name = $term->name;
        $this->slug = $term->slug;
        $this->taxonomy = $term->taxonomy;
        $this->description = $term->description;
        $this->parent = $term->parent;
        $this->count = $term->count;
    }

    public function permalink() {
        if (!$this->__permalink) {
            $this->__permalink = get_permalink($this->id);
        }
        return $this->__permalink;
    }

}