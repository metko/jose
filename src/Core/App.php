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

class App {


    public function __construct() {
       // TODO 
        //new Timber();

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


        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
        
        // *****************    
        // Register the post type and models
        (new PostType())->init();

        // *****************    
        // Create default context
        Assets::getInstance()->init();
        
    }
    
    /**
     * Start the query by automatic stuff
     *
     * @return self
     */
    public function init_scope_context() {
        $this->context = Timber::context();
        $this->autoInjectModelToContext();
        return $this;
    }
    
    /**
     * return the actual state of the context
     *
     * @return array
     */
    public function get_context() {
        return $this->context;
    }

    /**
     * Pass varibale into the context
     *
     * @param  mixed $param1 
     * @param  mixed $param2
     * @return self
     */
    public function pass($param1 = null, $param2 = null) {

        if(!$param1) {
            return $this;
        }

        if(is_array($param1)) {
            $this->addArrayToContext($param1);
        }else if( isset($param2)) {
            $this->context[$param1] = $param2;
        }
       
        return $this;
        
    }
    
    /**
     * Add an array to the context
     *
     * @param  mixed $array
     * @return null
     */
    public function addArrayToContext(Array $array) {
        if( count($array)) {
            foreach($array as $key => $value) {
                $context[$key] = $value;
            }
        }
        $this->context = array_merge($this->context, $context);
    }

    //TODO
    /**
     * Auto inject post model to context
     * Depending of the current query
     *
     * @return void
     */
    public function autoInjectModelToContext() {

        if(is_archive()) {
            // TODO
            // dump('im a archive');
            // dump($context);
        }
    
        if(is_singular()) {
            // TODO
            // dump('im a page or a single');
            $singular_post = $this->context['posts'][0];
            $this->context['post'] = $singular_post;
            $this->context['wp_title'] = $singular_post->title;
            // dump($context);
        }

        if(is_404()) {
            // TODO
            // dump('im a page 404');
            dump($this->context);
        }
        
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