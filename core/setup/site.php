<?php

namespace Core\Setup;

use Core\Config;
use Timber\Menu;
use Timber\Timber;

class Site {

    public static $instance = null;
    public $timber = null;

    public function init () {
        $this->timber = new Timber();
        
        $this->defineLocation();
        
        
        $this->setContext();
        
        // set post type
        new \Core\Setup\PostType();
        
        // thee option etc...
        new \Core\Setup\Theme\Theme();
        
        // register menu
        new \Core\Setup\Theme\RegisterMenu();

        // registr scripts
        add_action('wp_enqueue_scripts', [$this, 'registerMainScripts']);

    }

    // set the default context
    // it will be merged with the context in app/context.php
    public function setContext() {

        add_filter( 'timber/context', function($context) {
            
            $context['menu'] = new Menu('main_menu');
            
            $data['env'] = getenv('WP_ENV');

            ob_start();
            yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
            $context['breadcrumb'] = ob_get_clean();
            
            // get data from app/context.php
            return array_merge($context, Config::getInstance()->context()) ;

        } );
        // if yoast ....
      
    }



    

  
    // Where the views .twig will be strored
    // By default, il the app folder
    // You can define another one in the app/config/app.php
    public function defineLocation() {
        Timber::$locations = array(
            dirname(dirname(WP_CONTENT_DIR)).'/app/views',
        );
    }

    // // function to register a new class model
    // public function registerPostClassMap() {
    //     add_filter( 'Timber\PostClassMap', [ $this, 'PostClassMap' ]);
    // }


    // Get site instance
    public static function getInstance() {
        if(! self::$instance) {
            self::$instance = new Site();
        }
        return self::$instance;

    }

    public function PostClassMap( $post_class ) {
        return [
            // "programmes" => "\App\Models\ProgramePost",
            "page" => "\App\Models\Page",
            // "post" => "\App\Models\Post",
            // "module" => "\App\Models\Post",
            // "project" => "\App\Models\ProjectPost"
        ];
    }


}