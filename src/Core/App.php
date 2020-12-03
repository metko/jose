<?php

namespace Jose\Core;

use Jose\Assets;
use Jose\Core\CacheHandler;
use Jose\Core\Exceptions\ClassNotFoundException;
use Jose\Core\Theme\RegisterMenu;
use Jose\Core\Theme\Theme;
use Jose\Utils\Config;
use Timber\Timber;

class App extends \Timber\Site {
    
    public $context = [];

    public $THEME_INITED = 0;

    public function __construct($site_name_or_id = null) 
    {
       
        // dump('constryuct');
        // *****************    
        // Load the constants
        require(dirname(__DIR__).'/Utils/constants.php');

         // *****************    
        // Load the constants
        require(dirname(__DIR__).'/Utils/helpers.php');

        // *****************    
        // Load Configuration file
        Config::getInstance()->init();

        // // *****************    
        // // Define the cache policy
        // (new CacheHandler())->init();

        // *****************    
        // Init theme configuration
        (new Theme())->init();
        
        // *****************    
        // Register the differents menus
        (new RegisterMenu())->init();

        // *****************    
        // Define the folders the views
        (new Views())->init();

        // // *****************    
        // // Register the post type/taxonomies/terms and models
        (new PostClass())->init();
        
        // // (new Taxonomies())->init();
        // // Activate the postclass og all class
        PostClassMap::getInstance()->apply();

        // *****************    
        // Create default contet
        // // TODO Reset choose a way to handle the error template
        // $whoops = new \Whoops\Run;
        // $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        // $whoops->register();
        new Timber();
        parent::__construct($site_name_or_id);
        
    }

    public function init_theme() {
        
        if($this->THEME_INITED === 1) {
            return;
        }

        // *****************    
        // Create default context
        Assets::getInstance()->init();

        (new Plugins())->init();

        $this->THEME_INITED = 1;

        return $this;
    }
    
    /**
     * Pass varibale into the context
     *
     * @param  mixed $param1 
     * @param  mixed $param2
     * @return self
     */
    public function pass($param1 = null, $param2 = null) 
    {

        Context::getInstance()->pass($param1, $param2);
        return $this;
        
    }
   
    /**
     * Use Timber render function to output twig file, with context and cache
     *
     * @param  mixed $template
     * @return 
     */
    public function render(String $template) 
    {
    
        // merge the 2
        $this->context = Context::getInstance()->get();
        $this->autoInjectModelToContext();
        // dd($this->context);
        // $b = ob_get_clean();
        Timber::render($template.'.twig', $this->context);
    }

    //TODO
    /**
     * Auto inject post model to context
     * Depending of the current query
     *
     * @return void
     */
    public function autoInjectModelToContext() 
    {

        if(is_singular()) {
            // TODO
            // dump('im a page or a single');
            $singular_post = $this->context['posts'][0];
            $this->context['post'] = $singular_post;
            $this->context['wp_title'] = $singular_post->title;
            // dump($context);
        }

        if(is_404()) {
        }
        
    }

    /**
     * Get a new post with the and model associated
     * Leave empty to get the post default
     *
     * @param  mixed $model
     * @return void
     */
    public function getPost(Int $id = null, String $model ) 
    {
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

    /**
     * Get the class model name with the config object
     *
     * @return void
     */
    public function getClassModelName($model) 
    {
        // Get config key model
        $config = Config::getInstance()->get('models');

        // Get the value of the models=>location
        $path = array_key_exists('location', $config)
                ?  ROOT . $config['location']
                : ROOT . 'app/models/';

        // generate the good one
        return  $path.$model.'Model';
    }

}