<?php

namespace Jose\Core;

use Jose\Assets;
use Jose\Core\CacheHandler;
use Jose\Core\Exceptions\ClassNotFoundException;
use Jose\Core\Theme\RegisterMenu;
use Jose\Core\Theme\Theme;
use Jose\Models\Page;
use Jose\Models\PageModel;
use Jose\Utils\Config;
use Timber\Post;
use Timber\Timber;

class App extends  \Timber\Site {


    public function __construct( $site_name_or_id = null ) {
        new Timber();
        // dump('init once');

        // *****************    
        // Load the constants
        require(dirname(__DIR__).'/Utils/constants.php');

         // *****************    
        // Load the constants
        require(dirname(__DIR__).'/Utils/helpers.php');

        // *****************    
        // Load Configuration file
        Config::getInstance()->init();

        // *****************    
        // Define the cache policy
        (new CacheHandler())->init();

    
        // *****************    
        // Init theme configuration
        (new Theme())->init();

        // *****************    
        // Register the differents menus
        (new RegisterMenu())->init();

        // *****************    
        // Define the folders the views
        (new Views())->init();

        // *****************    
        // Create default contet
        (new Context())->init();

        // *****************    
        // Create default context
        Assets::getInstance()->init();

        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();

        // Use default class for all post types, except for pages.
        add_filter( 'Timber\PostClassMap', function() {
            return ["page" => "\Jose\Models\PageModel"];
        }, 10, 2 );

      
        
        // merge the two file to get the last key in $config

        
        // helpers

        // load the plugins


        // add the end, build the app
     
    }

    /**
     * Get  the instance og the site
     *
     * @return void
     */
    public function context(Array $array =  [] ) {

        $context = Timber::context();
        if( count($array)) {
            foreach($array as $key => $value) {
                $context[$key] = $value;
            }
        }

        $this->context = $context;
        $this->getDefaultPostContext();
        return $this;
        
    }


    public function getDefaultPostContext() {
        //dd($this->context['posts'][0]->post_type);
    }

    
    /**
     * Retreive the current post
     *
     * @return void
     */
    public function post(String $modelType = null) {

        // If model type is passed, grab the model associated instead
        if($modelType) {
            $this->context['post'] = $this->getPostModel($modelType);
        }else {
            $this->context['post'] = new Post();
        }
        return $this;
    }

     /**
     * Retreive the current post
     *
     * @return void
     */
    public function page(String $modelType = null) {

        // If model type is passed, grab the model associated instead
        if($modelType) {
            $this->context['post'] = $this->getPostModel($modelType);
        }else {
            $this->context['page'] = new PageModel();
        }

        return $this;
    }
    
    /**
     * Use Timber render function to output twig file, with context and cache
     *
     * @param  mixed $template
     * @return 
     */
    public function render(String $template) {
        

        Timber::render($template.'.twig', $this->context);
    }

    
    /**
     * Get the class model name with the config object
     *
     * @return void
     */
    public function getClassModelName($model) {
        // Get config key model
        $config = Config::getInstance()->get('models');

        // Get the value of the models=>location
        $path = array_key_exists('location', $config)
                ?  ROOT . $config['location']
                : ROOT . 'app/models/';

        // generate the good one
        return  $path.$model.'Model';
    }


    /**
     * Get the model of your choise by passing the name of the post type
     * Leave empty to get the post default
     *
     * @param  mixed $model
     * @return void
     */
    public function getPostModel(String $model, Int $id = null) {
        
        $class = $this->getClassModelName($model);

        // Check if the class exists
        if(class_exists($class)) {

            // TODO: Refactor with ID arg
            if($id) {
                return new $class($id);
            }else {
                return new $class();
            }

        }else {
            throw new ClassNotFoundException($class);
        }
    }



  

}