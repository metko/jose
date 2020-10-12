<?php

namespace Jose;

use Jose\Core\Exceptions\ManifestAssetsNotFound;
use Jose\Utils\Config;
use Jose\Utils\Finder;

class Assets {

    public static $instance = null;    

   
        
    /**
     * init
     *
     * @return void
     */
    public function init() {
        $this->config = Config::getInstance()->get("assets");

        if(Finder::getInstance()->fileExists(get_template_directory() . '/assets/manifest.json')) {
            $this->manifest = file_get_contents(get_template_directory() . '/assets/manifest.json');
        }else {
            throw new ManifestAssetsNotFound();
        }

        add_action('wp_enqueue_scripts', [$this, 'registerMainScripts']);
    }
    
    /**
     * loadDefaultScript
     *
     * @param  mixed $type
     * @return void
     */
    function loadDefaultScript($type) {
        // get main css file name
        if($this->config) {

            foreach($this->config as $assets) {
                dump($this->config);
                foreach($assets as $asset) {
                    $this->$type($asset);
                }
               
            } 
        }else {
            $this->$type("app.".$type);
        }
    }
   
    /**
     * registerMainScripts
     *
     * @return void
     */
    function registerMainScripts() {
        
        $this->js("runtime.js");  
        $this->loadDefaultScript("js");  
        $this->loadDefaultScript("css");  
    }

       
    /**
     * css
     *
     * @param  mixed $file
     * @param  mixed $groupe
     * @return void
     */
    function css($file, $groupe = null) {

        if(!$groupe) {
            $groupe = $this->getGroup($file);
        }
        wp_register_style($groupe, assets($file), null);
        wp_enqueue_style($groupe);
    }
 
    /**
     * js
     *
     * @param  mixed $file
     * @param  mixed $groupe
     * @return void
     */
    function js($file, $groupe = null) {

        if(!$groupe) {
            $groupe = $this->getGroup($file);
        }
        wp_register_script($groupe, assets($file), [], '1.0.0', true);
        wp_enqueue_script($groupe);
    }

    /**
     * getGroup
     *
     * @param  mixed $file
     * @return void
     */
    function getGroup($file) {

        $filename = explode('.', $file);
        $group =  $filename[0]."_style";
        
        return $group;
    }

     /**
     * getInstance
     *
     * @return 
     */
    public static function getInstance() {

        if(! self::$instance) {
            self::$instance = new Assets();
        }
        return self::$instance;
    } 


    public function getManifest() {
        $manifest_string = file_get_contents(get_template_directory() . '/assets/manifest.json');
        $manifest_array  = json_decode($manifest_string, true);
    
        if(!is_array($manifest_array)) return null;
    
        if(array_key_exists($key, $manifest_array)) {
            return $manifest_array[$key];
        }   
        
        if(WP_ENV == "development") {
            // throw new ErrorException('Array key ' . $key .' doesnt exist in manifest.json');
        }
    
        return false;
    }

}