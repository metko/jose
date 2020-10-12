<?php
Namespace Jose\Core;

use Jose\Utils\Config;
use Timber\Timber;
use Timber\Loader;
use Env\Env;

class CacheHandler {

    public static $instance = null;

    public $cacheConfig = null;

    
    /**
     * init
     *
     * @return void
     */
    public function init() {
        
        // get the cache object
        $this->cacheConfig = Config::getInstance()->get('cache');

        $this->initTimberCache();
        
    }

    /**
     * initTimberCache
     *
     * @return void
     */
    public function initTimberCache() {

        
      
        if(WP_ENV == "development"){
            // $loader = new Loader(); 
            // $loader->clear_cache_twig();   
            // Timber::$twig_cache = true;
            // Timber::$cache = false;
        } else {
            $this->setCacheLocation();
            Timber::$cache = true;
        }
    }

    
    /**
     * setCacheLocation
     *
     * @return void
     */
    public function setCacheLocation() {
        // Get the cache file path
        $cacheLocaction = $this->cacheConfig['location'] 
            ? ROOT. $this->cacheConfig['location'] 
            : APP."views/_cached";

        add_filter( 'timber/cache/location', function() use ($cacheLocaction) {
            return $cacheLocaction;
        });
    }
    
    /**
     * getInstance
     *
     * @return void
     */
    public static function getInstance() {
        if( ! self::$instance) {
            self::$instance = new CacheHandler();
        }
        return self::$instance;
    }
}