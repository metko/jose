<?php

namespace Jose;

use Jose\Core\Exceptions\ManifestAssetsNotFound;
use Jose\Utils\Config;
use Jose\Utils\Finder;

class Assets {

    public static $instance = null;    

    public $manifest = null;
        
    /**
     * init
     *
     * @return void
     */
    public function init() {
        $this->config = Config::getInstance()->get("assets");

        if($this->config['manifest']) {
            if(Finder::getInstance()->fileExists(get_template_directory() . '/assets/'.$this->config['manifest'])) {
                $this->manifest = json_decode(file_get_contents(get_template_directory() . '/assets/'.$this->config['manifest']), true);
            }else {
                throw new ManifestAssetsNotFound();
            }
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

            foreach($this->config as $key => $assets) {

                // if key == css || js
               if($key == $type) {
                   //      css|js      path
                   foreach($assets as $asset) {
                       $this->$type($asset);
                   }
                   
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
        
        // $this->js("runtime.js");  
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
        wp_register_style($groupe,  $this->assetPath($file), null);
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

        
        wp_register_script($groupe, $this->assetPath($file), [], '1.0.0', true);
        wp_enqueue_script($groupe);
    }
    
    /**
     * js
     *
     * @param  mixed $file
     * @param  mixed $groupe
     * @return void
     */
    function assetPath($key) {
        if($this->manifest) {
            dump( $this->manifest[$key]);
            return get_stylesheet_directory_uri() . '/assets' . $this->manifest[$key];
        } else {
            return get_stylesheet_directory_uri() . '/assets/' . $key;
        }
     
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