<?php

namespace Jose\Posts;
use ErrorException;

class PostType {

    public $name = "";

    public $public_name = null;
    public $plural_name = null;
    public $icon = null;
    public $slug = null;
    public $labels = null;
    public $arguments = null;

    /**
     * Register post type methods
     *
     * @return void
     */
    public  function create ()
    {
        $this->checkNames();

        if( ! $this->icon){
            $this->setIcon('dashicons-editor-customchar');
        }

        if( ! $this->labels){
            $this->setLabels([]);
        }

        if( ! $this->arguments){
            $this->setArguments([]);
        }

        $this->arguments['labels'] = $this->labels;


        return register_post_type($this->name, $this->arguments);
    }

    public function checkNames() {
        if( ! $this->public_name){
            $this->setPublicName(ucFirst($this->name));
        }

        if( ! $this->plural_name){
            $this->setPluralName($this->public_name . 's');
        }
    }

    /**
     * get_post_type_public_name
     *
     * @param $name
     * @return PostType
     */
    public function setName($name): PostType
    {
        $this->name = strtolower($name);
        return $this;
    }

    /**
     * get_post_type_public_name
     *
     * @param $name
     * @return PostType
     */
    public function setPublicName($name): PostType
    {
        $this->public_name = $name;
        return $this;
    }

    /**
     * get_post_type_public_name
     *
     * @param $name
     * @return PostType
     */
    private function setPluralName($name): PostType
    {
        $this->plural_name = $name;
        return $this;
    }

    /**
     * get_post_type_public_name
     *
     * @param $icon
     * @return PostType
     */
    public function setIcon($icon): PostType
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * get_post_type_public_name
     *
     * @param bool $bool
     * @return Taxonomy
     */
    public function setSlug($string): PostType
    {
        $this->slug = $string;
        return $this;
    }


    /**
     * Generate the post type arguments if needed
     * @param array $arguments
     * @return PostType
     */
    public function setArguments(Array $arguments): PostType
    {
        $this->checkNames();
        
        $public_name = $this->public_name;
        $plural_name = $this->plural_name;

        $auto_arguments = [
            'description' => "Description of ". $public_name,
            'public' =>  true,
            'publicly_queryable' =>  true,
            'show_ui' =>  true,
            'show_in_menu' =>  true,
            'query_var' => true,
            // 'capability_type' => [$public_name, $plural_name],
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 5,
            'show_in_rest' => true,
            'menu_icon' => $this->icon,
            'supports' => ['title', 'page-attributes', 'thumbnail','revisions', 'editor', 'excerpt']
        ];

        $auto_arguments['rewrite'] = [
            'slug' =>  $this->slug ?? strtolower($plural_name),
            'with_front' => true,
        ];

        $this->arguments = array_merge($auto_arguments, $arguments);
        return $this;

    }


    /**
     * Generate the post type label if needed
     * @param array $labels
     * @return PostType
     */
    public function setLabels(Array $labels): PostType
    {

        $this->checkNames();
        // Check if we have a public singular name
        $public_name = $this->public_name;
        $plural_name = $this->plural_name;

        $auto_labels =   [
            'name'               => __( $public_name, 'Post type general name', 'jose' ),
            'singular_name'      => __( $public_name, 'Post type singular name', 'jose' ),
            'menu_name'          => __( $plural_name , 'admin menu', 'jose' ),
            'name_admin_bar'     => __( $plural_name , 'add new on admin bar', 'jose'),
            'add_new'            => __('Add '.$public_name, 'jose' ),
            'add_new_item'       => __('Add new '.$public_name, 'jose' ),
            'new_item'           => __('New '.$public_name, 'jose' ),
            'edit_item'          => __('Edit '.$public_name, 'jose' ),
            'view_item'          => __('View '.$public_name, 'jose' ),
            'all_items'          => __('All '.$plural_name, 'jose'),
            'search_items'       => __('Search '.$plural_name, 'jose'),
            'parent_item_colon'  => __('Parent :'.$plural_name , 'jose'),
            'not_found'          => __('No '.$plural_name.' found.', 'jose'),
            'not_found_in_trash' => __('No '.$plural_name.' found in Trash.', 'jose')
        ];

        $this->labels = array_merge($auto_labels, $labels);

        return $this;
    }

    /*
    * Return call back on pre get post archive of this post_type
    */
    public function onArchive($callback) {
        add_filter( 'pre_get_posts', function ($query) use($callback) {
            if( ! array_key_exists('post_type', $query->query)) return;
            if(!is_admin() && is_archive()  && $query->query['post_type'] === $this->name) {
                return $callback->call($this, $query);
            }
        } );
        return $this;
    }

}