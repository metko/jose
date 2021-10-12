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
    public function convert($page) {
        $this->id = $page->ID;
        $this->author_id = $page->post_author;
        $this->post_date = $page->post_date;
        $this->conte = $page->post_content;
        $this->title = $page->post_title;
        $this->status = $page->post_post_status;
        $this->comment_status = $page->comment_status;
        $this->excerpt = $page->post_excerpt;
        $this->slug = $page->post_name;
        $this->updated_at = $page->post_modified;
        $this->parent_id = $page->post_parent;
        $this->post_type =  $page->post_type;
        $this->comment_count = $page->comment_count;
    }

    public function content () {
        return $this->post_content;
    }

    public function permalink() {
        if (!$this->__permalink) {
            $this->__permalink = get_permalink($this->id);
        }
        return $this->__permalink;
    }
}