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
        /*
        * If requested page hhas a template
        */
        if (is_page_template()) {
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
        if (is_post_type_archive()) {

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
        if (is_author()) {
            // Setup the context
            // TODO: FIX AUTHOR CLASS PAGE
            $author_id = $this->queried_object->ID;
            $author_login = $this->queried_object->user_login;
            jose('context')->set('page', jose()->castPosts([$this->queried_object], 'user')[0]);
            jose('context')->set('posts', jose()->castPosts($this->posts));
            jose('context')->set('pagination', paginate_links(['type' => 'array']));

            // Maybe in the router
            if (array_key_exists("author:$author_id", Router::routes('archive'))) {
                $this->action = Router::routes('archive')["author:$author_id"];
            }
            if (array_key_exists("author:$author_login", Router::routes('archive'))) {
                $this->action = Router::routes('archive')["author:$author_login"];
            } 
            else if (array_key_exists('author', Router::routes('archive'))) {
                $this->action = Router::routes('archive')['author'];
            } 
            // or autloading class
            // else if (class_exists('\App\Controllers\UserController')) {
            //     $this->action = 'UserController@archive';
            // } 
            // Finnaly use the default class
            else {
                $this->action = 'PostController@archive';
            }
            // Set the correct fields for the context
        }

        /*
        * If the request is for a taxonomy archive
        */
        else if (is_category() || is_tax()) {
            // Setup the context
            $taxonomy = $this->queried_object->taxonomy;
            $term_name = $this->queried_object->slug;
            $term_id = $this->queried_object->term_id;
            jose('context')->set('page', jose()->castPosts([$this->queried_object], 'term:' . $taxonomy)[0]);
            jose('context')->set('posts', jose()->castPosts($this->posts));
            jose('context')->set('pagination', paginate_links(['type' => 'array']));
            if (array_key_exists("${taxonomy}:${term_id}" , Router::routes('taxonomy'))) {
                $this->action = Router::routes('taxonomy')["${taxonomy}:${term_id}"];
            }
            else if (array_key_exists("${taxonomy}:${term_name}" , Router::routes('taxonomy'))) {
                $this->action = Router::routes('taxonomy')["${taxonomy}:${term_name}"];
            }
            else if (array_key_exists("${taxonomy}" , Router::routes('taxonomy'))) {
                $this->action = Router::routes('taxonomy')["${taxonomy}"];
            } 
            // By default, check irf we have a TaxonomyController
            else if (class_exists('\App\Controllers\\' . ucfirst($taxonomy) . 'TaxonomyController', false)) {
                $this->action =  ucfirst($taxonomy) . 'TaxonomyController@index';
            }
            // No options, go to the post controller
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
            $post_type = $this->queried_object->post_type;
            $post_slug = $this->queried_object->post_name;
            $post_id = $this->queried_object->ID;
            jose('context')->set('post', jose()->castPosts([$this->queried_object], 'post:' . $post_type)[0]);

            if (array_key_exists("$post_type:$post_id", Router::routes('single'))) {
                $this->action = Router::routes('single')["$post_type:$post_id"];
            }
            else if (array_key_exists("$post_type:$post_slug", Router::routes('single'))) {
                $this->action = Router::routes('single')["$post_type:$post_slug"];
            }
            else if (array_key_exists($post_type, Router::routes('single'))) {
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
            $page_id = $this->queried_object->ID;
            $page_name = $this->queried_object->post_name;
            if (array_key_exists("page:$page_id", Router::routes('single'))) {
                // dd(Router::routes('single')['page']);
                $this->action = Router::routes('single')["page:$page_id"];
            }
            else if (array_key_exists("page:$page_name", Router::routes('single'))) {
                // dd(Router::routes('single')['page']);
                $this->action = Router::routes('single')["page:$page_name"];
            }
            else if (array_key_exists('page', Router::routes('single'))) {
                // dd(Router::routes('single')['page']);
                $this->action = Router::routes('single')['page'];
            }
            else {
                // dd(Router::routes('single')['page']);
                $this->action = 'PostController@page';
            }
        }

        /*
        * If the request is not found
        */
        else if (is_404()) {
            // TODO: PASS A GOOD CONTEXT
            if (array_key_exists('404', Router::routes())) {
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
