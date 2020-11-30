<?php

namespace Jose\Posts;
use ErrorException;
use Jose\Traits\useHooks;
use Jose\Utils\Config;
use Timber\Post;

class PostType extends Post {

    // public static $_pt_name = null;
    private $public_name = null;
    private $plural_name = null;
    
    /**
     * Register post type methods
     *
     * @return void
     */
    public  function register_post_type (): void
    {
        $this->set_name();
        $this->set_public_name();
        $this->set_plural_name();

        // // generer les labels
        $labels = $this->get_labels();

        // // generer les arguments
        $arguments = $this->get_arguments();
        $arguments['labels'] = $labels;
        
        register_post_type($this->name, $arguments);
        
        // get instance of post class map

    }
    
    /**
     * get_post_type_public_name
     *
     * @return void
     */
    private function set_name(): void
    {
        if( ! isset($this->name) ) {
            // must have the post type name
            if( ! property_exists(get_called_class(), 'name')) {
                throw new ErrorException('Need a static property _pt_name');
            }
        }
    }

    /**
     * get_post_type_public_name
     *
     * @return void
     */
    private function set_public_name(): void
    {
        if( isset($this->public_name) ) {
            $this->public_name = strtolower( $this->public_name);
        }else {
            $this->public_name = strtolower( $this->name);
        }
    }
    
    /**
     * get_post_type_public_name
     *
     * @return void
     */
    private function set_plural_name(): void
    {
        if(  isset($this->public_plural_name) ) {
            $this->plural_name = strtolower($this->plural_name);
        }else {
            $this->plural_name = $this->public_name."s";
        }
    }

    /**
     * Get class property if exists
     *
     * @param  String $key
     * @return String
     */
    private function get_class_property(string $key): ?string
    {
        if( isset($this->$key) ) {
            return $this->$key;
        }
        return null;
    }
    
    /**
     * Get the post type arguments with the property defined in the extends class
     *
     * @return array
     */
    public function get_arguments(): array 
    {   
        if(method_exists(get_called_class(), 'define_arguments')) {

            // use the labels return by the child class
            dump('use the built in class for argument');
            $arguments = $this->define_arguments(); 

            // if errors
            // TODO define proper error
            if( ! is_array($arguments) || empty($arguments)) {
                throw new ErrorException('The definition of the arguments must return an array');
            }

        }else {
            $arguments = $this->generate_arguments();
        }

        return $arguments;
    }
    
    /**
     * Generate the post type arguments if needed
     *
     * @return void
     */
    private function generate_arguments(): array
    {
        
        $public_name = $this->public_name;
        $plural_name = $this->plural_name;

        $arguments = [
            'description' => $this->get_class_property('description') ?? "Description of ". $public_name,
            'public' => $this->get_class_property('public') ?? true,
            'publicly_queryable' => $this->get_class_property('publicly_queryable') ?? true,
            'show_ui' => $this->get_class_property('show_ui') ?? true,
            'show_in_menu' => $this->get_class_property('show_in_menu') ?? true,
            'query_var' => $this->get_class_property('query_var') ?? true,
            // 'capability_type' => [$public_name, $plural_name],
            'has_archive' => $this->get_class_property('has_archive') ?? true,
            'hierarchical' => $this->get_class_property('hierarchical') ?? true,
            'menu_position' => $this->get_class_property('hierarchical') ?? null,
            'show_in_rest' => $this->get_class_property('show_in_rest') ?? true,
            'menu_icon' => $this->get_class_property('menu_icon') ?? 'dashicons-block-default',
            'supports' => $this->get_class_property('supports') ?? ['title', 'page-attributes', 'thumbnail','revisions', 'editor', 'excerpt']
        ];
        
        $arguments['rewrite'] = [
            'slug' =>  $this->get_class_property('slug') ?? $plural_name,
            'with_front' =>  $this->get_class_property('with_front') ?? true,
        ];
        
        return $arguments;

    }
    
    /**
     * Retreive the post type labels
     * If a function is set in the extended class, use it instead
     *
     * @return Array
     */
    public function get_labels(): array 
    {
        if(method_exists(get_called_class(), 'define_labels')) {

            // use the labels return by the child class
            dump('use the built in class');
            $labels = $this->define_labels(); 

            // if errors
            if( ! is_array($labels) || empty($labels)) {
                throw new ErrorException('The definition of the labels must be an array ');
            }

        }else {
            // auto generate the labels
            $labels = $this->generate_labels();
        }
        return $labels;
    }
    
    /**
     * Generate the post type label if needed
     *
     * @return Array
     */
    public function generate_labels(): array 
    {
        // Check if we have a public singular name 
        $public_name = $this->public_name;
        $plural_name = $this->plural_name;
        
        //Get local key
        // // TODO remove it before final build
        // define("ROOT",  dirname($_SERVER['DOCUMENT_ROOT'])."/");
        // define("APP",  ROOT."app/");
        // Config::getInstance()->init();
        $key = Config::getInstance()->get('local_key') ?? "jose";
        
        return  [
            'name'               => _x( ucFirst($public_name), 'Post type general name', $key ),
            'singular_name'      => _x( ucFirst($public_name), 'Post type singular name', $key ),
            'menu_name'          => _x( ucFirst($plural_name) , 'admin menu', $key ),
            'name_admin_bar'     => _x( ucFirst($plural_name) , 'add new on admin bar', $key),
            'add_new'            => _x('Add '.$public_name, $key ),
            'add_new_item'       => __('Add new '.$public_name, $key ),
            'new_item'           => __('New '.$public_name, $key ),
            'edit_item'          => __('Edit '.$public_name, $key ),
            'view_item'          => __('View '.$public_name, $key ),
            'all_items'          => __('All '.$plural_name, $key),
            'search_items'       => __('Search '.$plural_name, $key),
            'parent_item_colon'  => __('Parent :'.$plural_name , $key),
            'not_found'          => __('No '.$plural_name.' found.', $key),
            'not_found_in_trash' => __('No '.$plural_name.' found in Trash.', $key)
        ];
    }

}