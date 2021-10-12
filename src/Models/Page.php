<?php

namespace Jose\Models;

class Page extends BaseModel {
    public $id = '';
    public $author_id = '';
    public $post_date = '';
    public $post_content = '';
    public $title = '';
    public $status = '';
    public $comment_status = '';
    public $excerpt = '';
    public $slug = '';
    public $updated_at = '';
    public $parent_id = '';
    public $post_type = '';
    public $comment_count = '';
    public $hello = "salut";
    public $__permalink = null;
    public function __construct($post) {
        //dd('$post', $post);
        $this->convert($post);
    }
    public function convert($post) {
        // dd('$post', $post);
        $this->id = $post->ID;
        $this->author_id = $post->post_author;
        $this->post_date = $post->post_date;
        $this->post_content = $post->post_content;
        $this->title = $post->post_title;
        $this->status = $post->post_post_status;
        $this->comment_status = $post->comment_status;
        $this->excerpt = $post->post_excerpt;
        $this->slug = $post->post_name;
        $this->updated_at = $post->post_modified;
        $this->parent_id = $post->post_parent;
        $this->post_type =  $post->post_type;
        $this->comment_count = $post->comment_count;
    }

    public function content () {
        echo get_the_content($this->id);
    }

    public function permalink() {
        if (!$this->__permalink) {
            $this->__permalink = get_permalink($this->id);
        }
        return $this->__permalink;
    }
}