<?php

namespace Jose\Models;

class User extends BaseModel {

    public $id = '';
    public $roles = [];
    public $nickname = '';


    private $__permalink = null;
    
    public function __construct($post) {
        $this->convert($post);
    }
    public function convert($post) {
        $this->id = $post->ID;
        $this->roles = $post->roles;
        $metas = get_user_meta($this->id);
        $this->nickname = $metas['nickname'][0];
        $this->first_name = $metas['first_name'][0];
        $this->last_name = $metas['last_name'][0];
        $this->description = $metas['description'][0];
    }

    public function permalink() {   
        if (!$this->__permalink) {
            $this->__permalink = get_author_posts_url($this->id);
        }
        return $this->__permalink;
    }

}