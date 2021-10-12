<?php

namespace Jose;

use Jose\Exception\ClassDoesntExistException;

class Dispatcher
{
    public $template = null;
    public $posts = null;
    public $wp = null;
    public $queried_object = null;
    public $dispatched = true;
    public $action = null;

    public function __construct($queried_template)
    {
        global $posts;
        global $wp; 
        // dd($this->post);
        $this->posts = $posts;
        $this->wp = $wp;
        $this->queried_object = get_queried_object();
        $this->container = Container::getInstance();

        $path = explode("/", $queried_template);
        $file = explode('.', end($path));
        $this->template = $file[0];
        $this->parseRequest();
    }


    public function parseRequest() 
    {
        dd(is_post_type_archive());
        /*
        * If requested page hhas a template
        */
        if (isset($this->queried_object->ID)) {
            // check the value of the meta field template
            $this->action = get_post_meta( $this->queried_object->ID, '_wp_page_template', true);
            if ($this->action) {
                jose('context')->set('page', new \App\Models\Page($this->queried_object));
                // Retturn directly the execution of the controller
                return $this->dispatch();
            }
        }
        
        /*
        * If the request is for an post_type archive page
        */
        if (is_object($this->queried_object) && $this->queried_object instanceof \WP_Post_Type) {

            // Setup the context
            jose('context')->set('page', jose('post_type')->get($this->queried_object->name));
            jose('context')->set('posts', jose()->castPosts($this->posts, 'post:' . $this->queried_object->name));
            jose('context')->set('pagination', paginate_links(['type' => 'array']));

            $post_type = $this->queried_object->name;
            // Maybe in the router
            if (array_key_exists($post_type, Router::routes('archive'))) {
                $this->action = Router::routes('archive')[$post_type];
            } 
            // or autloading class
            else if (class_exists('\App\Controllers\\' . ucfirst($post_type) . 'Controller', false)) {
                $this->action =  ucfirst($post_type) . 'Controller@archive';
            } 
            // Finnaly use the default class
            else {
                $this->action = 'PostController@archive';
            }
            // Set the correct fields for the context
        }

        /*
        * If the request is for an author archive page
        */
        if (is_object($this->queried_object) && $this->queried_object instanceof \WP_User) {
            // Setup the context
            // TODO: FIX AUTHOR CLASS PAGE
            jose('context')->set('page', jose()->castPosts([$this->queried_object], 'user')[0]);
            jose('context')->set('posts', jose()->castPosts($this->posts));
            jose('context')->set('pagination', paginate_links(['type' => 'array']));

            // Maybe in the router
            if (array_key_exists('user', Router::routes())) {
                $this->action = Router::routes('user');
            } 
            // or autloading class
            else if (class_exists('\App\Controllers\UserController')) {
                $this->action = 'UserController@archive';
            } 
            // Finnaly use the default class
            else {
                $this->action = 'PostController@archive';
            }
            // Set the correct fields for the context
        }

        /*
        * If the request is for a taxonomy archive
        */
        else if (is_object($this->queried_object) && $this->queried_object instanceof \WP_Term) {
            // Setup the context
            jose('context')->set('page', jose()->castPosts([$this->queried_object], 'term:' . $this->queried_object->taxonomy)[0]);
            jose('context')->set('posts', jose()->castPosts($this->posts));
            jose('context')->set('pagination', paginate_links(['type' => 'array']));

            $taxonomy = $this->queried_object->taxonomy;
            if (array_key_exists($taxonomy, Router::routes('taxonomy'))) {
                $this->action = Router::routes('taxonomy')[$taxonomy];
            }
            else if (class_exists('\App\Controllers\\' . ucfirst($taxonomy) . 'TaxonomyController', false)) {
                $this->action =  ucfirst($taxonomy) . 'TaxonomyController@index';
            }
            else {
                $this->action = 'PostController@archive';
            }
            // dump('tax archive');
        } 


        /*
        * If the request is for a post single
        */
        else if (is_single()) {
            // Setup the context
            jose('context')->set('post', jose()->castPosts([$this->queried_object], 'post:' . $this->queried_object->post_type)[0]);

            $post_type = $this->queried_object->post_type;
            if (array_key_exists($post_type, Router::routes('single'))) {
                $this->action = Router::routes('single')[$post_type];
            }
            else if (class_exists('\App\Controllers\\' . ucfirst($post_type) . 'Controller', false)) {
                $this->action =  ucfirst($post_type) . 'Controller@single';
            }
            else {
                $this->action = 'PostController@single';
            }
        }
        
        /*
        * If the request is for the front_page (aka: homepage)
        */
        else if (is_front_page()) {
            // Setup the context
            jose('context')->set('page', new \App\Models\Page($this->queried_object));

            if (array_key_exists('frontpage', Router::routes())) {
                $this->action = Router::routes('frontpage');
            }
            else if (class_exists('\App\Controllers\FrontPageController', false)) {
                $this->action =  'FrontPageController@show';
            }
            else {
                $this->action = 'PageController@frontpage';
            }

        }

        /*
        * If the request is for the home (aka: post list page)
        */
        else if (is_home()) {
            // Setup the context
            jose('context')->set('page', new \App\Models\Page($this->queried_object));
            jose('context')->set('posts', jose()->castPosts($this->posts));
            jose('context')->set('pagination', paginate_links(['type' => 'array']));

            if (array_key_exists('home', Router::routes())) {
                $this->action = Router::routes('home');
            }
            else if (class_exists('\App\Controllers\HomeController', false)) {
                $this->action =  'HomeController@show';
            }
            else {
                $this->action = 'PageController@home';
            }
        }

        /*
        * If the request is for a page classic
        */
        else if (is_page()) {
            // Setup the context
            jose('context')->set('page', new \App\Models\Page($this->queried_object));
            $this->action = 'PageController@show';
        }

        /*
        * If the request is not found
        */
        else if (is_404()) {
            // TODO: PASS A GOOD CONTEXT
            if (array_key_exists('e_404', Router::routes())) {
                $this->action = Router::routes('e_404');
            } else if (class_exists('\App\Controllers\E_404Controller', false)) {
                $this->action =  'E_404Controller@index';
            } else {
                $this->action = 'PageController@e_404';
            }
        }

        /*
        * If the request is for search
        */
        else if (is_search()) {
            // Setup the context
            jose('context')->set('page', new \App\Models\Page($this->queried_object));
            jose('context')->set('posts', jose()->castPosts($this->posts));
            jose('context')->set('pagination', paginate_links(['type' => 'array']));

            $this->action = 'PostController@archive';
        }

        return $this->dispatch();
    }   

    public function dispatch() {
        if($this->template === 'index' && $this->action) {
            $this->execute($this->action);
        } else {
            $this->dispatched = false;
        }
    }

    public function execute($action) {
        $splited = explode('@', $action);
        $controller = '\App\Controllers\\' . ucFirst($splited[0]);
        $action = $splited[1];
        (new $controller)->$action();
    }

}
