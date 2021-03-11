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
        $this->config = Config::getInstance()->get('cache');

        if(is_null($this->config) || !is_array($this->config) ) {
            $this->config = [];
        } 
        $this->initTimberCache();
        
    }

    public function keyExists($key) {
        return array_key_exists($key, $this->config);
    }

    public function get($key) {

        if( ! $this->keyExists($key)) {
            return false;
        }
        return $this->config[$key];
    }

    /**
     * initTimberCache
     *
     * @return void
     */
    public function initTimberCache() {

      
        if(WP_ENV == "development"){
            if($this->get('in_development') != true ) {
                $this->setCacheLocation();
                $loader = new Loader(); 
                $loader->clear_cache_twig();   
            }

            Timber::$twig_cache =$this->get('in_development') ?? false;;
            Timber::$cache = $this->get('in_development') ?? false;

        } else {
            $this->setCacheLocation();
            Timber::$twig_cache = $this->get('in_production') ?? true;;
            Timber::$cache = $this->get('in_production') ?? true ;
        }

    }

    
    /**
     * setCacheLocation
     *
     * @return void
     */
    public function setCacheLocation() {
        // Get the cache file path
        $cacheLocaction = $this->get('location')
            ? ROOT. $this->get('location')
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