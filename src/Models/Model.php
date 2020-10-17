<?php

namespace Jose\Models;
use ErrorException;
use Jose\Traits\useHooks;
use Jose\Utils\Config;
use Timber\Post;

class Model extends Post {

    // public static $_pt_name = null;

    private static $_jose_pt_name = null;
    private static $_jose_pt_public_name = null;
    private static $_jose_pt_plural_name = null;
    
    /**
     * Register post type methods
     *
     * @return void
     */
    public static function register_post_type (): void
    {
        static::set_post_type_name();
        static::set_post_type_public_name();
        static::set_post_type_plural_name();

        // generer les labels
        $labels = self::get_post_type_labels();

        // generer les arguments
        $arguments = self::get_post_type_arguments();
        $arguments['labels'] = $labels;
        dd($arguments);

        //register_post_type(self::$_jose_pt_name, $arguments);

        // get instance of post class map
    }
    
    /**
     * get_post_type_public_name
     *
     * @return void
     */
    private static function set_post_type_name(): void
    {
        if( self::get_class_property('_pt_name') ) {
            self::$_jose_pt_name = strtolower(static::$_pt_name);
        }else {
            // must have the post type name
            if( ! property_exists(get_called_class(), '_pt_name')) {
                throw new ErrorException('Need a static property _pt_name');
            }
        }
    }

    /**
     * get_post_type_public_name
     *
     * @return void
     */
    private static function set_post_type_public_name(): void
    {
        if( self::get_class_property('_pt_public_name') ) {
            self::$_jose_pt_public_name = strtolower(static::$_pt_public_name);
        }else {
            self::$_jose_pt_public_name = strtolower(self::$_jose_pt_name);
        }
    }
    
    /**
     * get_post_type_public_name
     *
     * @return void
     */
    private static function set_post_type_plural_name(): void
    {
        if( self::get_class_property('_pt_public_plural_name') ) {
            self::$_jose_pt_plural_name = strtolower(static::$_pt_public_plural_name);
        }else {
            self::$_jose_pt_plural_name = self::$_jose_pt_public_name."s";
        }
    }

    /**
     * Get class property if exists
     *
     * @param  String $key
     * @return String
     */
    private static function get_class_property(string $key): ?string
    {
        if( property_exists(get_called_class(), $key) ) {
            return static::$$key;
        }
        return null;
    }
    
    /**
     * Get the post type arguments with the property defined in the extends class
     *
     * @return array
     */
    public static function get_post_type_arguments(): array 
    {   
        if(method_exists(get_called_class(), 'define_post_type_arguments')) {

            // use the labels return by the child class
            dump('use the built in class for argument');
            $arguments = static::define_post_type_arguments(); 

            // if errors
            // TODO define proper error
            if( ! is_array($arguments) || empty($arguments)) {
                throw new ErrorException('The definition of the arguments must return an array');
            }

        }else {
            $arguments = self::generate_post_type_arguments();
        }

        return $arguments;
    }
    
    /**
     * Generate the post type arguments if needed
     *
     * @return void
     */
    private static function generate_post_type_arguments(): array
    {
        
        $public_name = self::$_jose_pt_public_name;
        $plural_name = self::$_jose_pt_plural_name;

        $arguments = [
            'description' => self::get_class_property('_pt_description') ?? "Description of ". $public_name,
            'public' => self::get_class_property('_pt_public') ?? true,
            'publicly_queryable' => self::get_class_property('_pt_publicly_queryable') ?? true,
            'show_ui' => self::get_class_property('_pt_show_ui') ?? true,
            'show_in_menu' => self::get_class_property('_pt_show_in_menu') ?? true,
            'query_var' => self::get_class_property('_pt_query_var') ?? true,
            'capability_type' => [$public_name, $plural_name],
            'has_archive' => self::get_class_property('_pt_has_archive') ?? true,
            'hierarchical' => self::get_class_property('_pt_hierarchical') ?? true,
            'menu_position' => self::get_class_property('_pt_hierarchical') ?? null,
            'show_in_rest' => self::get_class_property('_pt_show_in_rest') ?? true,
            'menu_icon' => self::get_class_property('_pt_menu_icon') ?? 'dashicons-block-default',
            'supports' => self::get_class_property('_pt_supports') ?? ['title', 'page-attributes', 'thumbnail','revisions', 'editor', 'excerpt']
        ];
        $arguments['rewrite'] = [
            'slug' =>  self::get_class_property('_pt_slug') ?? $plural_name,
            'with_front' =>  self::get_class_property('_pt_with_front') ?? true,
        ];
        
        return $arguments;

    }
    
    /**
     * Retreive the post type labels
     * If a function is set in the extended class, use it instead
     *
     * @return Array
     */
    public static function get_post_type_labels(): array 
    {
        if(method_exists(get_called_class(), 'define_post_type_labels')) {

            // use the labels return by the child class
            dump('use the built in class');
            $labels = static::define_post_type_labels(); 

            // if errors
            if( ! is_array($labels) || empty($labels)) {
                throw new ErrorException('The definition of the labels must be an array ');
            }

        }else {
            // auto generate the labels
            $labels = self::generate_post_type_labels();
        }
        return $labels;
    }
    
    /**
     * Generate the post type label if needed
     *
     * @return Array
     */
    public static function generate_post_type_labels(): array 
    {
        // Check if we have a public singular name 
        $public_name = self::$_jose_pt_public_name;
        $plural_name = self::$_jose_pt_plural_name;
        
        //Get local key
        // TODO remove it before final build
        define("ROOT",  dirname($_SERVER['DOCUMENT_ROOT'])."/");
        define("APP",  ROOT."app/");
        Config::getInstance()->init();
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