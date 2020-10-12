<?php

namespace Jose\Core;

use Core\Setup\Theme\Theme as ThemeTheme;
use Jose\Core\CacheHandler;
use Jose\Core\ErrorsHandler;
use Jose\Core\Theme\RegisterMenu;
use Jose\Core\Theme\Theme;
use Jose\Utils\Config;
use Timber\Timber;

class App extends  \Timber\Site {


    public function __construct( $site_name_or_id = null ) {
        new Timber();
        // dump('init once');

        // *****************    
        // Load the constants
        require(dirname(__DIR__).'/utils/constants.php');

         // *****************    
        // Load the constants
        require(dirname(__DIR__).'/utils/helpers.php');

        // *****************    
        // Load Configuration file
        Config::getInstance()->init();

        // *****************    
        // Define the cache policy
        (new CacheHandler())->init();

        // *****************    
        // Define the cache policy
        (new ErrorsHandler())->init();

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
        // Create default contet
        Assets::getInstance()->init();

      
        
        // merge the two file to get the last key in $config

        
        // helpers

        // load the plugins


        // add the end, build the app
     
    }



  

}