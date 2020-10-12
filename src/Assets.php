<?php

namespace Jose;

use Jose\Utils\Config;

class Assets {

    public static $instance = null;    

   
        
    /**
     * init
     *
     * @return void
     */
    public function init() {
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
        if(Config::getInstance()->get("assets")) {

            foreach(Config::getInstance()->get("assets") as $assets) {
             ;
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

}