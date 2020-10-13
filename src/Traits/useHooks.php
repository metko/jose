<?php

namespace Jose\Traits;

Trait useHooks {
    
    /**
     * Register the basics hooks
     * - pre_get_post
     * - after_save
     * 
     * @return void
     */
    protected function registerHooks() {

        $that = $this;

        // *********************
        // Pre get archive and singleposts hooks
        add_action('pre_get_posts', function($query) use ($that) {

            if($that->isBasicQuery($query) && $that->isPostCurrentType($query) ) {

                if($query->is_archive()) {
                    $that->beforeGetPosts($query);
                }else if($query->is_single()) {
                    $that->beforeGetSinglePost($query);

                }
            }
         
        });

    }
    
    /**
     * Return if it's a basic query
     *
     * @param  mixed $query
     * @return void
     */
    protected function isBasicQuery($query) {
        if (is_admin() || is_search() || !$query->is_main_query() ) {
            return false;
        }
        return true;
    }
    
    /**
     * Check if the current query is for the current post type
     *
     * @param  mixed $query
     * @return boolean
     */
    protected function isPostCurrentType($query) {

        if(! array_key_exists('post_type', $query->query)) return false;
        return  $query->query['post_type'] == $this->unique_name;
    }
    
    
    /**
     * Before get posts archive or loop
     *
     * @param  mixed $query
     * @return void
     */
    public function beforeGetPosts($query) {
        //dump('beforeGetPosts');
    }
    
    /**
     * Before get single post
     *
     * @param  mixed $query
     * @return void
     */
    public function beforeGetSinglePost($query) {
        //dump('beforeGetSinglePost');
    }

}