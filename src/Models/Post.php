<?php

namespace Jose\Models;

class Post extends BaseModel {
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
    // CACHED VALUE
    public $__permalink = null;
    public $__published_at = null;

    public function __construct($post) {
        //dd('$post', $post);
        $this->convert($post);
    }
    public function convert($post) {
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

    public function author () {
        if (!$this->__author) {
            $this->__author = new User(get_user_by( 'id', $this->author_id ));
        }
        return $this->__author;
    }

    public function published_at () {
        if (!$this->__published_at) {
            $this->__published_at = \Jose\Classes\DateTime::published_at($this->updated_at);
        }
        return $this->__published_at;
    }

    public function full_date () {
        return \Jose\Classes\DateTime::full_date($this->updated_at);
    }

    public function thumbnail_src($size = 'thumbnail') {
        return wp_get_attachment_image_url(get_post_thumbnail_id($this->id), $size);
        // return get_the_post_thumbnail_url($this->id, );\
    }
}